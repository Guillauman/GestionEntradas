<?php
/**
 * @author     Guillermo Rodríguez García
 */
?>
<?php
//Reanudamos la sesión
session_start();

//Comprobamos si el usario está logueado
//Si no lo está, se le redirecciona al index que ,en este caso de que la sesión no haya caducado, irá al inicio. Si no, al login.
//Si lo está, definimos el botón de cerrar sesión y la duración de la sesión
if (!isset($_SESSION['user']) and $_SESSION['estado'] != 'Autenticado') {
    header('Location: index.php');
} else {
    $estado = $_SESSION['user'];
    $salir = '<a href="class/Logout.php" target="_self">Cerrar sesión</a>';
    require('class/Sesiones.php');
}
?>
<!DOCTYPE html>
<html>
    <head>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="css/inicio.css">
        <link rel="stylesheet" href="../css/bootstrap/bootstrap.min.css">

        <script defer src="fontawesome/js/solid.js"></script>
        <script defer src="fontawesome/js/fontawesome.js"></script>
        <script src="/js/jquery-3.4.1.min.js"></script> 


    </head>
    <body>

        <div class="sidebar">
            <div class="sidebar-header">
                <a  class="active" href="index"><i class="fas fa-home"></i> Inicio</a>
            </div>
            <a href="views/FormFichero"><i class="fas fa-upload"></i> Cargar</a>
            <a href="views/Listar"><i class="fas fa-list"></i> Listado</a>
            <a href="views/Resumen"><i class="fas fa-sticky-note"></i> Resumen</a>
            <a href="class/Logout"><i class="fas fa-sign-out-alt" ></i> Salir</a>
        </div>

        <div class="content">
            <span class="logo" style="float: right;margin: 20px"><img src="img/logo-siga-2017.png"/></span>
            <div class="descripcion">
                <h1>Gestión de Entradas y Salidas.</h1>
                <p>Esta es la aplicación de gestión de entradas y salidas del personal de la empresa SIGA 98.</p> 
            </div>            
        </div>
    </body>
</html>

