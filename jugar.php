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

$sqlSorteo = mysqli_query($conexion, "SELECT * FROM sorteo WHERE id='1'");
$sorteo = mysqli_fetch_assoc($sqlSorteo);

function disponibilidad($num)
{
    $result = '0';
    require("conexion_be.php");
    $idusuario = $_SESSION['usuario'];
    $sql = "SELECT * FROM detalle_jugadas WHERE numero='$num' AND idjugada IN(SELECT id FROM jugadas)";
    $ressql = mysqli_query($conexion, $sql);

    if (mysqli_num_rows($ressql) > 0) {
        $result = '1';
    }
    $sql = "SELECT * FROM jugadas_temp WHERE idusuario='$idusuario' AND numero='$num'";
    $ressql = mysqli_query($conexion, $sql);

    if (mysqli_num_rows($ressql) > 0) {
        $result = '2';
    }

    return $result;
}

function formatearNumero($numero, $valor)
{
    $longitud = strlen((string)$numero);
    $formato = "%0" . $longitud . "d";
    return sprintf($formato, $valor);
}
$numero = $sorteo['cantidad_num'];

$i = range(0, $numero);
$page = isset($_GET['page']) ? (int) $_GET['page'] : 1;
$limit = 505;
$offset = ($page - 1) * $limit;

$total_pages = ceil(count($i) / $limit);

if ($page > $total_pages) {
    $page = 1;
}

$paginated_i = array_slice($i, $offset, $limit);
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
                            <img src="<?=$sorteo['banner']?>" alt="" />
                        </div>
                        <div class="col-md-12">
                            <h2>Lista de Boletos</h2>
                            <h2 style="font-size: 20px; line-height: 1.6;">
                                Selecciona los boletos del tarjetón</h2>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div id="jugadas" style="font-size: 20px; line-height: 1.6;"></div>
                        </div>
                    </div>
                    <div class="row">
                        <div id="contenido" class="contenedor" style=" height: 600px; width: 100%; overflow-y: scroll;  background-color: #e9f6fe; ">
                            <?php
                            // mostramos los boletos
                            foreach ($paginated_i as $value) :
                                $check = disponibilidad(formatearNumero($numero, $value));
                                switch ($check) {
                                    case '1':
                                        echo '<div class="ficha-off" data-valor="' . formatearNumero($numero, $value) . '-' . $_SESSION['usuario'] . '">' . formatearNumero($numero, $value) . '</div>';
                                        break;
                                    case '2':
                                        echo '<div class="ficha-select" data-valor="' . formatearNumero($numero, $value) . '-' . $_SESSION['usuario'] . '">' . formatearNumero($numero, $value) . '</div>';
                                        break;
                                    default:
                                        echo '<a href="#" class="ficha-casino boton-juego" data-valor="' . formatearNumero($numero, $value) . '-' . $_SESSION['usuario'] . '">' . formatearNumero($numero, $value) . '</a>';
                                        break;
                                }
                            endforeach;
                            ?>
                        </div>
                        <!-- script que actualiza el div -->
                        <script src="assets/js/actualizar.js"></script>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <!-- paginacion -->
                            <nav aria-label="Page navigation example">
                                <ul class="pagination">
                                    <?php
                                    if ($page > 1) {
                                        $pagina_anterior = $page - 1;
                                        echo '<li class="page-item"><a class="page-link" href="?page=' . $pagina_anterior . '">Atrás</a></li>';
                                    }

                                    $pagina_siguiente = $page + 1;
                                    if ($pagina_siguiente <= $total_pages) {
                                        echo '<li class="page-item"><a class="page-link" href="?page=' . $pagina_siguiente . '">Siguiente</a></li>';
                                    }
                                    ?>
                                </ul>
                            </nav>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <!-- botones -->
                            <div class="col-md-6">
                                <h2 style="font-size: 20px; line-height: 1.6;">Elige tus boletos para continuar</h2>
                            </div>
                            <div class="col-md-6"><a href="checkout" class="submit">Comprar</a></div>
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

// Supongamos que 'error' es tu variable de sesión
if (isset($_SESSION['error']) && !empty($_SESSION['error'])) {
    $error = $_SESSION['error'];
    echo "<script>
        alertify.alert('Atención', '$error', function(){
            alertify.message(' ');
        });
    </script>";
    // Limpiar el mensaje de error después de mostrarlo
    $_SESSION['error'] = '';
}
?>