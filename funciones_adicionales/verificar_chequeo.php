<?php
    # Código para verificar si un chequeo 
    # ya se encuentra registrado o no.
    session_start();

    # Verificar si algún administrador no
    # ha iniciado su correspondiente sesión.
    if(!isset($_SESSION["ID_administrador"])) {
        header("location: ../index.php");
        die();
    }

    if($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET["ID-colaborador"], $_GET["fecha-chequeo"])) {
        # Iniciar y verificar la conexión
        # con la base de datos.
        $conexion_base = new mysqli("localhost", "root", "", "checadorumd");
        if($conexion_base->connect_error) {
            echo "false";
            die();
        }

        # Verificar si el chequeo está registrado.
        if($colaborador = $conexion_base->query("SELECT * FROM chequeo WHERE 
        ID_colaborador = '" . $_GET["ID-colaborador"] . "' AND fecha_chequeo = '" . $_GET["fecha-chequeo"] . "';")) {
            if($colaborador->num_rows > 0) {
                echo "true";
            }
            else {
                echo "false";
            }
        }
        else {
            echo "false";
        }

        # Cerrar la conexión con la base de datos.
        $conexion_base->close();
    }
    else {
        header("location: ../administrador/menu_principal/menu_administrador.php");
        die();
    }
?>