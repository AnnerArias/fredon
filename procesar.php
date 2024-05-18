<?php
require("conexion_be.php");
function formatearNumero($numero, $valor) {
    $longitud = strlen((string)$numero);
    $formato = "%0".$longitud."d";
    return sprintf($formato, $valor);
}
// Verificar si se ha recibido un valor a través de la petición GET
if(isset($_GET['valor'])){
    
    $dat=explode('-',$_GET['valor']);
    $valor = $dat[0];
    $idusuario=$dat[1];
    // echo 'Valor:'.$valor.' usiario:'.$idusuario;
    $idsorteo=1; //debe venir del form
    date_default_timezone_set('America/Caracas');
    $creado= date('Y-m-d h:i:s');
				$query=mysqli_query($conexion,"INSERT INTO jugadas_temp (idusuario,numero,fecha,id_sorteo,estatus) 
				VALUES('$idusuario','$valor', '$creado','1', 'Reservado')");
    // Aquí podrías realizar cualquier procesamiento adicional con el valor recibido
    // contar cuantos numeros tiene jugado este usario en la tabla jugadas_temp
    $count=mysqli_query($conexion,"SELECT count(id) FROM jugadas_temp WHERE idusuario='$idusuario' AND id_sorteo='$idsorteo'");
    $num=mysqli_fetch_array($count);
    // traer el costo_num de la tabla sorteo por el $idsorteo
    $cost=mysqli_query($conexion,"SELECT costo_num, moneda FROM sorteo WHERE  id='$idsorteo'");
    $costo=mysqli_fetch_array($cost);
    //calcular el costo de la jugada $num*$costo
    $costo_num=$costo['costo_num'];
    $costo_total=$costo_num*$num['count(id)'];

    $nSQL=mysqli_query($conexion,"SELECT numero FROM jugadas_temp WHERE idusuario='$idusuario' AND id_sorteo='$idsorteo'");
    while ($data=mysqli_fetch_array($nSQL)) {
        echo "<div class='submit'>".formatearNumero(10000,$data['numero'])."</div>";
    } 
    echo "<br>";
    //mostrar el costo_total solo si es mayor a 0
    if($costo_total>0){
        echo $costo_total." ".$costo['moneda'];
    }else{
        echo ' ';
    }
    
} else {
    // En caso de no recibir ningún valor, se muestra un mensaje de error
    echo "No se ha recibido ningún valor.";
}
?>