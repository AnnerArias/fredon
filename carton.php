<?php
session_start();
if(isset($_SESSION['id'])){
    $idusuario = $_SESSION['id'];
} else {
    $idusuario = 'Temp-' . rand(1,10000);
    $_SESSION['id'] = $idusuario;
}

//si se acaba de registrar o de ingresar pero antes selecciono algunos numeros
//actualizamos la tabla temporal
if(isset($_SESSION['tempID'])){
    $new=$_SESSION['id'];
    $old=$_SESSION['tempID'];
    $sql="UPDATE jugadas_temp SET idusuario='$new' WHERE idusuario='$old'";
	$ressql=mysqli_query($mysqli,$sql);
    unset($_SESSION['tempID']);
}
function disponibilidad($num){
    $result='0';
    require("conexion/conexion.php");
    $idusuario = $_SESSION['id'];
    $sql="SELECT * FROM detalle_jugadas WHERE numero='$num' AND idjugada IN(SELECT id FROM jugadas)";
	$ressql=mysqli_query($mysqli,$sql);

    if (mysqli_num_rows($ressql)>0) {
        $result='1';
    }
    $sql="SELECT * FROM jugadas_temp WHERE idusuario='$idusuario' AND numero='$num'";
	$ressql=mysqli_query($mysqli,$sql);

    if (mysqli_num_rows($ressql)>0) {
        $result='2';
    }

    return $result;
}
function formatearNumero($numero, $valor) {
    $longitud = strlen((string)$numero);
    $formato = "%0".$longitud."d";
    return sprintf($formato, $valor);
}
// traerlo de BD
$numero = 1000;

for ($i = 0; $i <= $numero; $i++) {
	$check=disponibilidad($i);
	switch ($check) {
		case '1':
			echo '<div class="ficha-off" data-valor="'.$i.'">'.formatearNumero($numero, $i).'</div>';
			break;
		case '2':
				echo '<div class="ficha-select" data-valor="'.$i.'">'.formatearNumero($numero, $i).'</div>';
			break;
		default:
				echo '<a href="#" class="ficha-casino boton-juego" data-valor="'.$i.'">'.formatearNumero($numero, $i).'</a>';
			break;
	}
	
}
?>