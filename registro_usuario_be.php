 <?php

    include 'conexion_be.php';

    $nombre_completo = $_POST['nombre_completo'];
    $email = $_POST['email'];
    $telefono = $_POST['telefono'];
    $password = $_POST['password'];
    
 //Password Encryption  
    $password = hash('sha512', $password);

    $query = "INSERT INTO usuarios(nombre_completo, email, telefono, password)
            VALUES('$nombre_completo', '$email', '$telefono', '$password')";

 //Verificar que el correo no se repita en la base de datos
    $verificar_correo = mysqli_query($conexion, "SELECT * FROM usuarios WHERE email ='$email' ");

    if(mysqli_num_rows($verificar_correo) > 0 ){
        echo '
        <script>
            alert("Este correo ya esta registrado, intenta con otro...");
            window.location = "/sistema";
        </script>    
        ';
        exit();
    }

 //Verificar que el telefono no se repita en la base de datos
    $verificarTelefono = mysqli_query($conexion, "SELECT * FROM usuarios WHERE telefono ='$telefono' ");

    if(mysqli_num_rows($verificarTelefono) > 0 ){
        echo '
        <script>
            alert("Este teléfono ya esta registrado, intenta con otro...");
            window.location = "/sistema";
        </script>    
        ';
        exit();
    }    
    
    $ejecutar = mysqli_query($conexion, $query);

    if($ejecutar){
        echo '
            <script>
                alert("Usuario almacenado exitosamente");
                window.location = "/sistema";
            </script>    
        ';
    }else{
        echo '
            <script>
                alert("Intentalo de nuevo, usuario no almacenado");
                window.location = "/sistema";
            </script>    
        ';
    }

    mysqli_close($conexion);

?>
