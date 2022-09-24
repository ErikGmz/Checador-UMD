<?php
    session_start();

    # Verificar si algún administrador no
    # ha iniciado su correspondiente sesión.
    if(!isset($_SESSION["ID_administrador"])) {
        header("location: ../index.php");
        die();
    }

    # Verificar si se envió un formulario
    # para cerrar sesión de administrador.
    if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST["cierre-sesion"])) {
        session_destroy();
        header("location: ../index.php");
        die();
    }
    else {
        header("location: menu_administrador.php");
        die();
    }
?>