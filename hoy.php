<?php
require("conexion_be.php");
// traerlo de BD
function formatearNumero($numero, $valor) {
    $longitud = strlen((string)$numero);
    $formato = "%0".$longitud."d";
    return sprintf($formato, $valor);
}
// Verificar si se ha recibido un valor a través de la petición GET
$jugadas=mysqli_fetch_assoc(mysqli_query($conexion,"SELECT * FROM jugadas_temp "));
if(isset($_GET['usuario'])){
    $idusuario = $_GET['usuario'];
    $count=mysqli_query($conexion,"SELECT count(id) FROM jugadas_temp WHERE idusuario='$idusuario' AND id_sorteo='1'");
    $num=mysqli_fetch_array($count);
    // traer el costo_num de la tabla sorteo por el 1
    $cost=mysqli_query($conexion,"SELECT costo_num, moneda FROM sorteo WHERE  id='1'");
    $costo=mysqli_fetch_array($cost);
    //calcular el costo de la jugada $num*$costo
    $costo_num=$costo['costo_num'];
    $costo_total=$costo_num*$num['count(id)'];

    $nSQL=mysqli_query($conexion,"SELECT numero FROM jugadas_temp WHERE idusuario='$idusuario' AND id_sorteo='1'");
    while ($data=mysqli_fetch_array($nSQL)) {
        echo "<div class='submit'>".formatearNumero(10000,$data['numero'])."</div>";
    } 
    echo "<br>";
    //mostrar el costo_total solo si es mayor a 0
    if($costo_total>0){
        echo $costo_total." ".$costo['moneda'];
    }else{
        echo '';
    }
} else {
    // En caso de no recibir ningún valor, se muestra un mensaje de error
    // date_default_timezone_set('America/Caracas'); 
    // echo "Seleccione un numero.".date("d-m-Y H:i:s");
}


?>