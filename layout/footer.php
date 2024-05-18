    <section class="footer">
            <div class="container">
                <div class="col-md-6">
                    <div style="margin-top: 20px;">
                        <img src="assets/images/logo_color.png" />
                    </div>
                    
                    <form action="#">
                        <div>

                            <div class="row">
                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <input type="text" class="form-control" name="name"
                                            placeholder="Nombre" required="">
                                    </div>
                                </div>
                            </div>


                            <div class="row">
                                <div class="col-sm-12" style="margin-bottom: ;">
                                    <div class="form-group">
                                        <input type="email" class="form-control" name="email"
                                            placeholder="Email" required="">
                                    </div>
                                </div>
                               
                            </div>


                            <div class="form-group">
                                <textarea class="form-control" name="message" rows="7"
                                    placeholder="Mensaje"></textarea>
                            </div>

                            <div class="form-group">
                                <input type="submit" value="Enviar" class="btn btn-lg">
                            </div>
                        </div>
                    </form>
                </div>
                <div class="col-md-6" style="margin-top: 20px;">
                    <div class="col-md-6">
                        <h2 style="color: #fff; text-decoration: none;">Empresa</h2>
                        <a href="#" style="color: #fff; text-decoration: none; font-size: large;">Sobre nosotros</a><br>
                        <a href="#" style="color: #fff; text-decoration: none; font-size: large;">Políticas de privacidad</a><br>
                        <a href="#" style="color: #fff; text-decoration: none; font-size: large;">Políticas del sorteo</a>
                    </div>
                    <div class="col-md-6">
                        <h2 style="color: #fff; text-decoration: none;">Legal</h2>
                        <a href="#" style="color: #fff; text-decoration: none; font-size: large;">Términos de uso</a><br>
                        <a href="#" style="color: #fff; text-decoration: none; font-size: large;">Acuerdo de licencia</a><br>
                        <a href="#" style="color: #fff; text-decoration: none; font-size: large;">Información de copyright</a><br>
                        <a href="#" style="color: #fff; text-decoration: none; font-size: large;">Políticas de cookies</a><br>
                        <a href="#" style="color: #fff; text-decoration: none; font-size: large;">Información de cookies</a>
                    </div>
                </div>
                
            </div>
            <div class="row">
                <div class="col-md-12 copyright">
                   <div style="margin-top: 20px;">FredOn &copy; todos los derechos reservados</div> 
                </div>
            </div>
        </section><!-- End off footer Section-->











    </div>

    <!-- START SCROLL TO TOP  -->

    <div class="scrollup">
        <a href="#"><i class="fa fa-chevron-up"></i></a>
    </div>

    <script src="assets/js/vendor/jquery-1.11.2.min.js"></script>
    <script src="assets/js/vendor/bootstrap.min.js"></script>

    <script src="assets/js/jquery.magnific-popup.js"></script>
    <script src="assets/js/jquery.mixitup.min.js"></script>
    <script src="assets/js/jquery.easing.1.3.js"></script>
    <script src="assets/js/jquery.masonry.min.js"></script>

    <!--slick slide js -->
    <script src="assets/css/slick/slick.js"></script>
    <script src="assets/css/slick/slick.min.js"></script>

    <script src="assets/js/plugins.js"></script>
    <script src="assets/js/main.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/AlertifyJS/1.13.1/alertify.min.js" integrity="sha512-JnjG+Wt53GspUQXQhc+c4j8SBERsgJAoHeehagKHlxQN+MtCCmFDghX9/AcbkkNRZptyZU4zC8utK59M5L45Iw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script>
            $(document).ready(function(){
                $.get("hoy.php", {usuario: "<?php echo $_SESSION['usuario']; ?>"}, function(data){
                    $("#jugadas").html(data);
                });
            });
        </script>
</body>

</html>