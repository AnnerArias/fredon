<?php
//destruir las session y redirigir a index.php
session_start();
if($_SESSION['correo']){	
	session_destroy();
	header("location:index");
}
else{
	header("location:index");
}
?>