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
$idSorteo = $_GET['s'];
$idjugada = $_GET['e'];
$sqlSorteo = mysqli_query($conexion, "SELECT * FROM sorteo WHERE id='$idSorteo '");
$sorteo = mysqli_fetch_assoc($sqlSorteo);

function formatearNumero($numero, $valor)
{
    $longitud = strlen((string)$numero);
    $formato = "%0" . $longitud . "d";
    return sprintf($formato, $valor);
}
$email = $_SESSION['usuario'];
$sqlUsuario = mysqli_query($conexion, "SELECT * FROM usuarios WHERE email='$email'");
$dataUsuario = mysqli_fetch_assoc($sqlUsuario);

$sql = mysqli_query($conexion, "SELECT * FROM jugadas WHERE id='$idjugada'");
$data = mysqli_fetch_assoc($sql);
$monto = $data['monto'];

//  traer todos los datos e la tabla ajustes
$sql_ajustes = mysqli_query($conexion, "SELECT * FROM ajustes WHERE id='1' ");
$dataAjustes = mysqli_fetch_assoc($sql_ajustes);

$sql_bancos = mysqli_query($conexion, "SELECT * FROM bancos ");

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
                <div class="col-sm-6 col-xs-12" style="text-align:center; background-color: #e9f6fe; border-radius: 10px; border: 1px solid #fff;">
                    <div class="row">
                        <div style="margin: 5px 5px 5px 5px; border:1px solid #a8adad; border-radius: 5px 5px 5px 5px;">
                            <img src="<?= $sorteo['banner'] ?>" alt="" />
                        </div>
                        <div class="col-md-12">
                            <h1 style="font-size: 40px;">Método de Pago</h1>
                            <h2 style="text-transform: none;">
                                <?php
                                //buscar los datos del usuario

                                echo " Elige tu método de pago</h2>";

                                ?>
                                <br>
                        </div>
                    </div>
                    <br>
                    <div class="row">
                        <div class="col-md-12">
                            <!-- bancos -->
                            <form action="finalizar" method="post" enctype="multipart/form-data">
                                <label for="banks">Elige un banco:</label>
                                <select name="banks" id="banks" class="form-control">
                                    <option value="0">Seleccione...</option>
                                    <?php
                                    while ($row = mysqli_fetch_row($sql_bancos)) {
                                        echo '<option value="' . $row[0] . '">' . $row[1] . '</option>';
                                    }
                                    ?>

                                </select><br><br>

                                <?php
                                $sql_ban = mysqli_query($conexion, "SELECT * FROM bancos ");
                                while ($row = mysqli_fetch_assoc($sql_ban)) {

                                    echo '
                                            <div id="bank' . $row['id'] . '" style="display:none; text-align:left; background-color:#fff; color:#000">
                                                <p><b>Número de cuenta:</b> ' . $row['numero'] . '
                                                <br><b>Titular:</b> ' . $row['titular'] . '
                                                <br><b>RUT:</b> ' . $row['rut'] . '
                                                <br><b>Tipo de cuenta:</b> ' . $row['tipo'] . '</p>
                                            </div>
                                            ';
                                }
                                ?>


                                <div id="upload" style="display:block;">
                                    <label for="baucher">Soporte de pago:</label>
                                    <input type="file" id="baucher" name="baucher" class="form-control">
                                </div>

                                <input type="checkbox" id="later" name="later" value="later">
                                <label for="later"> ¿Desea cargar el soporte en otro momento?</label><br><br>
                                <input type="hidden" name="jugada" value="<?= $data['id'] ?>">
                                <input type="hidden" name="sorteo" value="1">
                                <input type="hidden" name="idusuario" value="<?= $_SESSION['usuario'] ?>">
                                <input type="submit" class="submit" value="Finalizar">
                            </form>

                            <script>
                                document.getElementById('banks').addEventListener('change', function() {
                                    // Obtén todos los divs
                                    var divs = document.querySelectorAll('div[id^="bank"]');

                                    // Oculta todos los divs
                                    for (var i = 0; i < divs.length; i++) {
                                        divs[i].style.display = 'none';
                                    }

                                    // Muestra el div seleccionado
                                    document.getElementById('bank' + this.value).style.display = 'block';
                                });

                                document.getElementById('later').addEventListener('change', function() {
                                    document.getElementById('upload').style.display = this.checked ? 'none' : 'block';
                                });
                            </script>

                            <!-- fin de bancos -->
                        </div>
                    </div>
                </div>
            </div>
        </div><!--End of row -->
    </div>
    <!--End of container -->
</section>
<!-- fin del contenido -->
<?php
require("layout/footer.php");
?>