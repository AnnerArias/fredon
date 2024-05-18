<?php
session_start();

require("conexion/conexion.php");
$correo=$_POST['email'];
$clave=$_POST['clave'];
//$bets=$clave;
$bets=sha1(trim($clave));
$sql=mysqli_query($mysqli,"SELECT * FROM usuarios WHERE correo='$correo'");
if($f=mysqli_fetch_array($sql))
	{
		if($bets==$f['clave']){
			$_SESSION['id']=$f['id'];
			$_SESSION['nombre']=$f['nombre'];
			$_SESSION['correo']=$f['correo'];
			$_SESSION['foto']=$f['foto'];
			$_SESSION['rol']=$f['rol'];
			$_SESSION['estatus']=$f['estatus'];
			$_SESSION['fecha_reg']=$f['fecha_reg'];
			
			if ($f['estatus']=='Activo') {
				if ($f['rol']=='Admin') {
					header("Location: dashboard.php?id=".$f['id']."");
				}
				if ($f['rol']=='Cliente') {
					header("Location: jugar.php?id=".$f['id']."");
				}
			}else{
				header('Location: index.php');
			}
		}else{
			header('Location: index.php');
		}
	}else{
		header('Location: index.php');
	}
?>