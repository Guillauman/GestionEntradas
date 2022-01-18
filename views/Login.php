<?php
/**
 * @author     Guillermo Rodríguez García
 */
?>
<link href="css/bootstrap/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
<script src="js/bootstrap.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<!------ Include the above in your HEAD tag ---------->

<!DOCTYPE html>
<html>
    <head>
        <title>Login Page</title>
        <!--Made with love by Mutiullah Samim -->

        <!--Bootsrap 4 CDN-->
        <link rel="stylesheet" href="css/bootstrap/bootstrap.min.css">
        <!--Fontawesome CDN-->
        <link rel="stylesheet" href="fontawesome/css/all.css">
        <!--Custom styles-->
        <link rel="stylesheet" type="text/css" href="css/login.css">
    </head>
    <body>
        <div class="container">
            <div class="d-flex justify-content-center h-100">
                <div class="card">
                    <div class="card-header">
                        <h4>ENTRADAS</h4>   
                    </div>

                    <div id="mensaje" style="border:1px #CCC;border-collapse: collapse; padding:8px;color:red"></div>

                    <div class="card-body">
                        <form name="login" id='login' action='class/ValidaLogin.php' method='post' >
                            <div class="input-group form-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fas fa-user"></i></span>
                                </div>
                                <input type="text" class="form-control" placeholder="Usuario" name="user" required>

                            </div>
                            <div class="input-group form-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fas fa-key"></i></span>
                                </div>
                                <input type="password" class="form-control" placeholder="Contraseña" name="pass" required>
                            </div>
                            <div class="form-group">
                                <input type="submit" value="Login" class="btn float-right login_btn">
                            </div>
                        </form>
                    </div>

                </div>
            </div>
        </div>
        <script>

//Guardamos el controlador del div con ID mensaje en una variable
            var mensaje = $("#mensaje");
//Ocultamos el contenedor
            mensaje.hide();

            $("#login").on("submit", function (e) {
                //Evitamos que se envíe por defecto
                e.preventDefault();
                //Creamos un FormData con los datos del mismo formulario
                var formData = new FormData(document.getElementById("login"));

                //Llamamos a la función AJAX de jQuery
                $.ajax({
                    //Definimos la URL del archivo al cual vamos a enviar los datos
                    url: "class/ValidaLogin.php",
                    //Definimos el tipo de método de envío
                    type: "POST",
                    //Definimos el tipo de datos que vamos a enviar y recibir
                    dataType: "HTML",
                    //Definimos la información que vamos a enviar
                    data: formData,
                    //Deshabilitamos la caché
                    cache: false,
                    //No especificamos el contentType
                    contentType: false,
                    //No permitimos que los datos pasen como un objeto
                    processData: false
                }).done(function (echo) {
                    //Una vez que recibimos respuesta
                    //comprobamos si la respuesta no está vacía
                    if (echo !== "") {
                        //Si hay respuesta (error), mostramos el mensaje
                        mensaje.html(echo);
                        mensaje.slideDown(500);
                    } else {
                        //Si no hay respuesta, redirecionamos a donde sea necesario
                        //Si está vacío, recarga la página
                        window.location.replace("");
                    }
                });
            });
        </script>
    </body>
</html>
