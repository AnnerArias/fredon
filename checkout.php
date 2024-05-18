<?php

session_start();

if (!isset($_SESSION['usuario'])) {
    echo '
            <script>
            alert("Debes de Iniciar Sesion");
            window.location = "/sistema";
            </script>
        ';
    session_destroy();
    die();
}
require("conexion_be.php");
$idusuario = $_SESSION['usuario'];

function formatearNumero($numero, $valor)
{
    $longitud = strlen((string)$numero);
    $formato = "%0" . $longitud . "d";
    return sprintf($formato, $valor);
}
$email = $_SESSION['usuario'];
$sqlUsuario = mysqli_query($conexion, "SELECT * FROM usuarios WHERE email='$email'");
$dataUsuario = mysqli_fetch_assoc($sqlUsuario);
// contar cuantos numeros tiene jugado este usario en la tabla jugadas_temp
$count = mysqli_query($conexion, "SELECT count(id) FROM jugadas_temp WHERE idusuario='$idusuario' AND id_sorteo='1'");
$num = mysqli_fetch_array($count);
// traer el costo_num de la tabla sorteo por el 1
$cost = mysqli_query($conexion, "SELECT * FROM sorteo WHERE  id='1'");
$costo = mysqli_fetch_array($cost);
if ($num['count(id)'] < $costo['min_num']) {
    $_SESSION['error'] = "Debes elegir minimo ".$costo['min_num']." de boletos para ese sorteo";
    header('Location: jugar');
}
//calcular el costo de la jugada $num*$costo
$costo_num = $costo['costo_num'];
$costo_total = $costo_num * $num['count(id)'];
?>

<?php
require("layout/header.php");
?>
<!-- cotenido -->
<section id="home" class="history sections">
    <div class="container">
        <div class="row">
            <div class="main_history">
                <div class="col-sm-6 hidden-xs">
                    <div class="single_history_img">
                        <img src="assets/images/chica.png" alt="" />

                    </div>
                </div>
                <div class="col-sm-6 col-xs-12" style="text-align: center; background-color: #e9f6fe; border-radius: 10px; border: 1px solid #fff;">
                    <div class="row">
                        <div style="margin: 5px 5px 5px 5px; border:1px solid #a8adad; border-radius: 5px 5px 5px 5px;">
                            <img src="<?= $costo['banner'] ?>" alt="" />
                        </div>
                        <div class="col-md-12">
                            <h1 style="font-size: 40px;">Verifica!</h1>
                            <h2 style="text-transform: none;">
                                <?php
                                //buscar los datos del usuario

                                echo " Seleccionados</h2>";

                                $nSQL = mysqli_query($conexion, "SELECT numero FROM jugadas_temp WHERE idusuario='$idusuario' AND id_sorteo='1'");
                                while ($data = mysqli_fetch_array($nSQL)) {
                                    echo "<div class='submit'>" . formatearNumero(10000, $data['numero']) . "</div>";
                                }
                                ?>
                                <br>
                                <b>Cantidad de boletos: <?= $num['count(id)'] ?> Total: <?= number_format($costo_total, 2, ',', '.') . " " . $costo['moneda'] ?></b>
                        </div>
                    </div>
                    <br>
                    <div class="row">
                        <div class="col-md-12">
                            <form action="generar_pedido.php" method="POST">
                                <input type="hidden" name="idsorteo" value="<?=$costo['id']?>">
                                <input type="hidden" name="monto" value="<?=$costo_total?>">

                                <div>

                                    <div class="row">
                                        <div class="col-sm-12">
                                            <div class="form-group" style="text-align:left;">
                                                <b>Datos personales</b>
                                                <input type="text" class="form-control" name="name" value="<?= $dataUsuario['nombre_completo'] ?>" placeholder="Nombre y apellidos" required="" style="background-color:#c2e8ff; border: none; border-radius:8px; color:#000;">
                                            </div>
                                        </div>
                                    </div>


                                    <div class="row">
                                        <div class="col-sm-12">
                                            <div class="form-group" style="text-align:left;">
                                                <b>Correo electrónico</b>
                                                <input type="email" class="form-control" readonly name="email" value="<?= $dataUsuario['email'] ?>" placeholder="Correo electrónico" required="" style="background-color:#c2e8ff; border: none; border-radius:8px; color:#000;">
                                                <small>Por seguridad el correo no puede ser modificado</small>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-sm-12">
                                            <div class="form-group" style="text-align:left;">
                                                <b>Teléfono celular</b>
                                                <input type="text" class="form-control" name="telefono" value="<?= $dataUsuario['telefono'] ?>" placeholder="Teléfono celular" required="" style="background-color:#c2e8ff; border: none; border-radius:8px; color:#000;">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-12">
                                            <!-- botones -->
                                            <div class="col-md-6"><a href="jugar" class="atras">Resgresar</a></div>
                                            <div class="col-md-6"><input type="submit" value="Continuar" class="submit"></div>
                                        </div>
                                    </div>

                                </div>
                            </form>

                        </div>
                    </div>

                </div>

            </div>
        </div>
        <!--End of row -->
    </div>
    <!--End of container -->
</section>
<!-- fin del contenido -->
<?php
require("layout/footer.php");
?>