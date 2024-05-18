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
    $idsorteo=1; //debe venir del form
    $creado= date('Y-m-d h:i:s');
    //eliminar un registro segun $valor
    mysqli_query($conexion,"DELETE FROM jugadas_temp WHERE idusuario='$idusuario' AND numero='$valor' AND id_sorteo='$idsorteo'");
				
           
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

    if($costo_total>0){
        echo $costo_total." ".$costo['moneda'];
    }
} else {
    // En caso de no recibir ningún valor, se muestra un mensaje de error
    // echo "No se ha recibido ningún valor.";
}
?>