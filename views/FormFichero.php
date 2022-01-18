<?php
/**
 * @author     Guillermo Rodríguez García
 */
?>
<?php
//Reanudamos la sesión
session_start();

//Comprobamos si el usario está logueado
//Si no lo está, se le redirecciona al index
//Si lo está, definimos el botón de cerrar sesión y la duración de la sesión
if (!isset($_SESSION['user']) and $_SESSION['estado'] != 'Autenticado') {
    header('Location: ../index.php');
} else {
    $estado = $_SESSION['user'];
    $salir = '<a href="class/Logout.php" target="_self">Cerrar sesión</a>';
    require('../class/Sesiones.php');
}
?>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">

        <title>Cargar</title>

        <link rel="stylesheet" href="../css/bootstrap/bootstrap.min.css">
        <link rel="stylesheet" href="../css/inicio.css">
        <link rel="stylesheet" href="../css/formulario.css">

        <!-- Font Awesome JS -->

        <script defer src="../fontawesome/js/solid.js"></script>
        <script defer src="../fontawesome/js/fontawesome.js"></script>
    </head>

    <body>
        <div class="wrapper">
            <!-- Sidebar  -->
            <div class="sidebar">
                <div class="sidebar-header">
                    <a  class="active" href="../index"><i class="fas fa-home"></i> Inicio</a>
                </div>
                <a href="FormFichero"><i class="fas fa-upload"></i> Cargar</a>
                <a href="Listar"><i class="fas fa-list"></i> Listado</a>
                <a href="Resumen"><i class="fas fa-sticky-note"></i> Resumen</a>
                <a href="../class/Logout"><i class="fas fa-sign-out-alt"></i> Salir</a>
            </div>

            <div class="content">
                <div class="cargarCSV" >   
                    <form action="Cargar.php" method="post" id="form1" enctype="multipart/form-data">
                        <p>Selecciona un archivo con extensión .csv:</p>    
                        <p> <input type="file" name="fichero"/></p>
                        <button class="buttonload" type="submit" value="Cargar" id="btnCargar">
                            Cargar <i class="fas fa-cloud-upload-alt"></i>
                        </button>
                    </form>
                </div>
            </div>

            <script src="../css/bootstrap/bootstrap.min.css"></script>
        </div>

    </body>

</html>

