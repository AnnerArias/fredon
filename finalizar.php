<?php

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    require("conexion_be.php");

    // Recoge los datos del formulario
    $banco = $_POST['banks'];
    $metodo = 'Transferencia';
    $jugada = $_POST['jugada'];
    $sorteo = $_POST['sorteo'];

    $idusuario = $_POST['idusuario'];
    $soporte = '';

    // Verifica si se ha subido un archivo
    if (isset($_FILES['baucher']) && $_FILES['baucher']['error'] == 0) {
        // Ruta donde se almacenarán los archivos
        $target_dir = "uploads/pagos/";

        // Genera un número aleatorio de tres dígitos
        $random_number = sprintf('%03d', rand(0, 999));
        
        // Obtiene la fecha actual en el formato 'd/m/Y'
        $date = date('d-m-Y');
        
        // Combina el número aleatorio y la fecha para formar el nuevo nombre del archivo
        $new_filename = $random_number . '-' . $date . '.' . pathinfo($_FILES["baucher"]["name"], PATHINFO_EXTENSION);
        
        // Ruta completa del archivo
        $target_file = $target_dir . $new_filename;
        $status = 'Pendiente';
        // Mueve el archivo subido al directorio 'uploads'
        if (move_uploaded_file($_FILES["baucher"]["tmp_name"], $target_file)) {
            $soporte = $target_file;
            $status = 'En proceso';
        } else {
            echo "Hubo un error al subir el archivo.";
        }
        
    }

    mysqli_query($conexion,"UPDATE jugadas SET idpago='$banco', metodo='$metodo', comprobante='$soporte', status_pago='$status' WHERE usuario='$idusuario' AND id='$jugada'");
header("Location: jugar");

}
?>
