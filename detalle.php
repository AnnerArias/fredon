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
$idjugada = $_POST['id'];
$email = $_SESSION['usuario'];
$sqlUsuario = mysqli_query($conexion, "SELECT * FROM usuarios WHERE email='$email'");
$sqlJugadas = mysqli_query($conexion, "SELECT * FROM jugadas WHERE id='$idjugada'");
$sqlSorteo = mysqli_query($conexion, "SELECT * FROM sorteo WHERE id IN(SELECT idsorteo FROM jugadas WHERE id='$idjugada')");
$sorteo = mysqli_fetch_assoc($sqlSorteo);
$sqlResultados = mysqli_query($conexion, "SELECT * FROM resultados WHERE idsorteo IN(SELECT idsorteo FROM jugadas WHERE id='$idjugada')");
$sqlPremios = mysqli_query($conexion, "SELECT * FROM premios WHERE idsorteo IN(SELECT idsorteo FROM jugadas WHERE id='$idjugada')");
$sqlDetalle = mysqli_query($conexion,"SELECT * FROM detalle_jugadas WHERE idjugada='$idjugada'");

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
                                <i class="fa fa-ticket"></i>
                            </span>
                            <span class="panel-title"> Ticket</span>
                        </div>
                        <div class="panel-body pb5">
                            <div id="ticket" style="width: 500px; height: 300px; position: relative; background-image: url('assets/images/ticket.png');">
                                <p style="position: absolute; top: 10px; left: 10px;">Jugador: <?=$usuario['nombre_completo']?></p>
                                <p style="position: absolute; top: 50px; left: 10px;">Fecha: <?=$sorteo['fecha']?></p>
                                <!-- Agrega el resto de tus elementos aquí -->
                            </div>

                            <button onclick="descargar()">Descargar</button>
                            <button onclick="imprimir()">Imprimihr</button>
                            <script>
                                function descargar() {
                                    html2canvas(document.querySelector("#ticket")).then(canvas => {
                                        var link = document.createElement('a');
                                        link.download = 'ticket.png';
                                        link.href = canvas.toDataURL()
                                        link.click();
                                    });
                                }

                                function imprimir() {
                                    var div = document.getElementById('ticket');

                                    html2canvas(div).then(function(canvas) {
                                        var imgData = canvas.toDataURL('image/png');
                                        var pdf = new jsPDF();

                                        pdf.addImage(imgData, 'PNG', 0, 0);
                                        pdf.autoPrint();
                                        window.open(pdf.output('bloburl'), '_blank');
                                    });
                                }
                            </script>

                        </div>
                    </div>
                </div>
                <div class="col-md-8">

                    <div class="tab-block">
                        <ul class="nav nav-tabs">
                            <li class="active">
                                <a href="#tab1" data-toggle="tab">Detalles de la jugada</a>
                            </li>
                        </ul>
                        <div class="tab-content p30" style="height: 730px;">
                            <div id="tab1" class="tab-pane active">
                                <table class="table table-light">
                                    
                                    <tbody>
                                        <tr>
                                            <td><b>Sorteo</b></td>
                                            <td><?= $sorteo['nombre'] ?></td>
                                        </tr>
                                        <tr>
                                            <td><b>Fecha del Sorteo</b></td>
                                            <td><?= $sorteo['fecha'] ?></td>
                                        </tr>
                                        <tr>
                                            <td><b>Premio</b></td>
                                            <td>
                                                <?php
                                                while ($row = mysqli_fetch_assoc($sqlPremios)) {
                                                    echo "<b>".$row['categoria']."</b> ".$row['titulo']."<br>"; 
                                                }
                                                ?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><b>Resultado</b></td>
                                            <td>
                                            <?php
                                            if(mysqli_num_rows($sqlResultados)>0){
                                              while ($row = mysqli_fetch_assoc($sqlResultados)) {
                                                    echo "<b>".$row['categoria']."</b> ".$row['titulo']."<br>"; 
                                                }  
                                            }else{
                                                echo"No disponibles";
                                            }
                                                
                                                ?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><b>Números seleccionados</b></td>
                                            <td>
                                            <?php
                                                while ($row = mysqli_fetch_assoc($sqlDetalle)) {
                                                    echo '<span class="badge badge-pill badge-primary">'.$row['numero'].'</span>  '; 
                                                }
                                                ?>
                                            </td>
                                        </tr>
                                        

                                    </tbody>
                                </table>
                                <div class="card">
                                <?php
                                $jugada = mysqli_fetch_assoc($sqlJugadas);
                                ?>
                                        <div class="card-header">
                                            Información del pago
                                        </div>
                                    <div class="card-body">
                                        <table class="table table-light">
                                            <tbody>
                                                <tr>
                                                    <td><b>Estado del pago</b></td>
                                                    <td><?=$jugada['status_pago']?></td>
                                                </tr>
                                                <tr>
                                                    <td><b>Metodo</b></td>
                                                    <td>
                                                    <?=$jugada['metodo']?>
                                                    <?php
                                                    if($jugada['metodo'] == 'Transferencia'){
                                                        $id = $jugada['idpago'];
                                                    $banco = mysqli_fetch_assoc(mysqli_query($conexion,"SELECT nombre FROM bancos WHERE id='$id'"));
                                                    echo ' ('.$banco['nombre'].')';
                                                    }
                                                    ?> 
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td><b>Monto</b></td>
                                                    <td><?=number_format($jugada['monto'], 2, ',', '.')?>  <?=$sorteo['moneda']?></td>
                                                </tr>
                                                <?php
                                                if($jugada['status_pago']!='Pendiente'){
                                                ?>
                                                <tr>
                                                    <td><b>Comprobante de pago</b></td>
                                                    <td>
                                                    <img class="img-thumbnail img-responsive" width="200px" height="75px" src="<?=$jugada['comprobante']?>" alt="">
                                                    </td>
                                                </tr>
                                                <?php
                                                }
                                                ?>                            
                                            </tbody>
                                        </table>
                                        <?php
                                        if($jugada['status_pago']=='Pendiente'){
                                        ?>
                                            <form action="finalizar" method="post" enctype="multipart/form-data">
                                            <label for="baucher">Soporte de pago:</label>
                                            <input type="file" id="baucher" name="baucher" class="form-control">

                                                <input type="submit" value="Cargar recibo de pago">
                                            </form>
                                        <?php
                                        }
                                        ?>
                                    </div>
                                </div>
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