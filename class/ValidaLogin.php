<?php

/**
 * @author     Guillermo Rodríguez García
 */
?>
<?php
error_reporting(E_ALL ^ E_NOTICE);
include './Usuario.class.php';
$userClass = new Usuario();
$usuario = $_POST['user'];
$pass = md5($_POST['pass']);

if (empty($usuario) || empty($pass)) {
    header("Location: ../index.php");
    exit();
}

$result = $userClass->getUsuario($usuario);
$usuario = $result->user;
if ($usuario == "") {
    die('<script>$(".login").val("");</script> Usuario incorrecto!');
    exit();
} else if ($result->pass == $pass) {
    session_start();
    $_SESSION['user'] = $result->user;
    $_SESSION['estado'] = 'Autenticado';
} else {
    die('<script>$(".login").val("");</script>Contraseña incorrecta!');
    exit();
}
?>