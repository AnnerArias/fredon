<?php
session_start();

if (!isset($_SESSION['usuario'])) {
    echo '
            <script>
            alert("Debes de Iniciar Sesion");
            window.location = "sistema";
            </script>
        ';
    session_destroy();
    die();
}
require("conexion_be.php");
$email = $_SESSION['usuario'];
$sqlUsuario = mysqli_query($conexion, "SELECT * FROM usuarios WHERE email='$email'");
$sqlJugadas = mysqli_query($conexion, "SELECT * FROM jugadas WHERE usuario='$email'");
$usuario = mysqli_fetch_assoc($sqlUsuario);

function formatearNumero($numero, $valor)
{
    $longitud = strlen((string)$numero);
    $formato = "%0" . $longitud . "d";
    return sprintf($formato, $valor);
}
?>

<?php
require("layout/header.php");
?>
<!-- cotenido -->


<section id="home" class="history sections">
    <div class="container">
        <section id="content">
            <!-- Begin .page-heading -->
            <div class="page-heading">
                <div class="media clearfix">
                    <div class="media-body va-m">
                        <h2 class="media-heading"><?= $usuario['nombre_completo'] ?>
                            <small> - Cliente</small>
                        </h2>
                        <div class="pull-right">
                        <button onclick="goBack()">Regresar</button>
                            <script>
                            function goBack() {
                                window.history.back();
                            }
                            </script>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-4">
                    <div class="panel">
                        <div class="panel-heading">
                            <span class="panel-icon">
                                <i class="fa fa-user"></i>
                            </span>
                            <span class="panel-title"> Información de contacto</span>
                        </div>
                        <div class="panel-body pb5">
                        <p class="lead"><b>Correo </b><br>
                        <a href="mailto:<?= $usuario['email'] ?>"><?= $usuario['email'] ?></a>
                        <br><br>
                        <b>Teléfono: </b><br>
                        <a href="tel:<?= $usuario['telefono'] ?>"><?= $usuario['telefono'] ?></a>
                        </p>
                            

                        </div>
                    </div>
                </div>
                <div class="col-md-8">

                    <div class="tab-block">
                        <ul class="nav nav-tabs">
                            <li class="active">
                                <a href="#tab1" data-toggle="tab">Actividad</a>
                            </li>
                        </ul>
                        <div class="tab-content p30" style="height: 730px;">
                            <div id="tab1" class="tab-pane active">
                                <table class="table table-light">
                                    <thead class="thead-light">
                                        <tr>
                                            <th>#</th>
                                            <th>Sorteo</th>
                                            <th>Monto</th>
                                            <th>Pago</th>
                                            <th>Fecha</th>
                                            <th>Resultado</th>
                                            <th>Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $i = 1;
                                        while ($row = mysqli_fetch_assoc($sqlJugadas)) {
                                            $timestamp = strtotime($row['fecha']);
                                            $fecha = date('d-m-Y', $timestamp);
                                        ?>
                                            <tr>
                                                <td><?= $i++ ?></td>
                                                <td><?= $row['idsorteo'] ?></td>
                                                <td><?= $row['monto'] ?></td>
                                                <td><?= $row['status_pago'] ?></td>
                                                <td><?= $fecha ?></td>
                                                <td><?= $row['estatus'] ?></td>
                                                <td>
                                                    <form action="detalle.php" method="post">
                                                        <input type="hidden" name="id" value="<?= $row['id'] ?>">
                                                        <input type="submit" value="Detalle">
                                                    </form>
                                                    
                                                </td>
                                            </tr>
                                        <?php
                                        }
                                        ?>

                                    </tbody>
                                </table>
                            </div>
                            <div id="tab2" class="tab-pane"></div>

                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
    <!--End of container -->
</section>

<!-- fin del contenido -->
<?php
require("layout/footer.php");
?>


?>