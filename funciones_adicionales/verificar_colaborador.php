<?php
    # Código para verificar si el ID de un
    # colaborador ya se encuentra registrado o no.
    session_start();

    # Verificar si algún administrador no
    # ha iniciado su correspondiente sesión.
    if(!isset($_SESSION["ID_administrador"])) {
        header("location: ../index.php");
        die();
    }

    if($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET["ID-colaborador"])) {
        # Iniciar y verificar la conexión
        # con la base de datos.
        $conexion_base = new mysqli("localhost", "root", "", "checadorumd");
        if($conexion_base->connect_error) {
            echo "false";
            die();
        }

        # Verificar si el colaborador está registrado.
        if($colaborador = $conexion_base->query("SELECT * FROM colaborador WHERE 
        ID = '" . $_GET["ID-colaborador"] . "';")) {
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