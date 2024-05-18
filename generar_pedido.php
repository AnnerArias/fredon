<?php
session_start();
include 'conexion_be.php';
// datos del usuario 
$name = $_POST['name'];
$telefono = $_POST['telefono'];
// datos del sorteo
$idsorteo = $_POST['idsorteo'];
$idusuario = $_SESSION['usuario'];
$monto = $_POST['monto'];
// echo $monto.$idsorteo.$idusuario;die;

// verificar si los datos de name, telefno sean los mismos o cambiaron
$sql_usuario = mysqli_query($conexion, "SELECT * FROM usuarios WHERE email='$idusuario'");
$row_usuario = mysqli_fetch_array($sql_usuario);
if ($row_usuario['nombre_completo'] != $name || $row_usuario['telefono'] != $telefono) {
    mysqli_query($conexion, "UPDATE usuarios SET nombre_completo='$name', telefono='$telefono' WHERE email='$idusuario'");
}

// verificr que los numeros no hayan sido comprados anteriormente
function disponibilidad($num, $idsorteo)
{
    $result = '0';
    require("conexion_be.php");
    $idusuario = $_SESSION['usuario'];
    // corregir para buscar por sorteo
    $sql = "SELECT * FROM detalle_jugadas WHERE numero='$num' AND idjugada IN(SELECT id FROM jugadas WHERE idsorteo='$idsorteo')";
    $ressql = mysqli_query($conexion, $sql);
    if (mysqli_num_rows($ressql) > 0) {
        $result = '1';
    }
    return $result;
}
// buscamos los numeros elegidos por el usuario en esta sesion para compararlos
$temp = mysqli_query($conexion, "SELECT * FROM jugadas_temp WHERE idusuario='$idusuario' AND id_sorteo='$idsorteo'");
// recoremos y comparamos
while ($numeros_temp = mysqli_fetch_array($temp)) {
    $numero = $numeros_temp['numero'];
    $id = $numeros_temp['numero'];
    $status = disponibilidad($numero, $idsorteo);
    if ($status == '1') {
        // eliminamos el numero
        mysqli_query($conexion, "DELETE FROM jugadas_temp WHERE id='$id'");
    // enviar un error
        $_SESSION['error'] = "El boleto ".$numero." no esta disponible";
       header("Location: jugar");die;
    }
}



// $sql_sorteo = mysqli_query($conexion, "SELECT * FROM sorteo WHERE id='$idsorteo'");
// $row_sorteo = mysqli_fetch_array($sql_sorteo);


mysqli_query($conexion, "INSERT INTO jugadas (usuario,idsorteo,metodo,monto,estatus,status_pago) VALUES ('$idusuario','$idsorteo','No selecionado','$monto','No disponible','Pendiente')");
// buscamos la ultima jugada 
$ultimo = mysqli_fetch_row(mysqli_query($conexion, "SELECT MAX(id) FROM jugadas"));
$idjugadas = $ultimo[0];

// recorrer uno a uno los numeros de la jugadas_temp
$temp = mysqli_query($conexion, "SELECT * FROM jugadas_temp WHERE idusuario='$idusuario' AND id_sorteo='$idsorteo'");
$i = 0;
while ($numeros_temp = mysqli_fetch_array($temp)) {
    $numero = $numeros_temp['numero'];

    $i++;
    $status = disponibilidad($numero, $idsorteo);
    if ($status == '0') {
        mysqli_query($conexion, "INSERT INTO detalle_jugadas (idjugada,numero) VALUES ('$idjugadas','$numero')");
    }
}
mysqli_query($conexion, "DELETE FROM jugadas_temp WHERE idusuario='$idusuario' AND id_sorteo='$idsorteo'");
// redirigir a /pagar
header("Location: pagar?e=".$idjugadas."&s=".$idsorteo."");
