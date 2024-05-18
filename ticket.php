<?php
// ticket.php

header('Content-Type: image/png');

// Carga la imagen de fondo
$imagen = imagecreatefrompng('assets/images/chica.png');

// Define los colores
$negro = imagecolorallocate($imagen, 0, 0, 0);

// Define las variables
$jugador = 'Jugador 1';
$fecha = date('Y-m-d');
$sorteo = 'Sorteo 123';
$premio = 'Premio Mayor';
$numeros = '12, 23, 34, 45, 56';
$total = 'Total Jugado: 100';
$metodo = 'Método de Pago: Transferencia';
$estado = 'Estado del Pago: Pagado';
$vencimiento = 'Fecha de Vencimiento: ' . date('Y-m-d', strtotime('+1 year'));

// Añade el texto a la imagen
imagettftext($imagen, 20, 0, 10, 50, $negro, 'arial.ttf', $jugador);
imagettftext($imagen, 20, 0, 10, 100, $negro, 'arial.ttf', $fecha);
imagettftext($imagen, 20, 0, 10, 150, $negro, 'arial.ttf', $sorteo);
imagettftext($imagen, 20, 0, 10, 200, $negro, 'arial.ttf', $premio);
imagettftext($imagen, 20, 0, 10, 250, $negro, 'arial.ttf', $numeros);
imagettftext($imagen, 20, 0, 10, 300, $negro, 'arial.ttf', $total);
imagettftext($imagen, 20, 0, 10, 350, $negro, 'arial.ttf', $metodo);
imagettftext($imagen, 20, 0, 10, 400, $negro, 'arial.ttf', $estado);
imagettftext($imagen, 20, 0, 10, 450, $negro, 'arial.ttf', $vencimiento);

// Genera la imagen
imagepng($imagen);

// Libera la memoria
imagedestroy($imagen);
?>
