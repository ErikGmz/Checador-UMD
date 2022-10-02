<?php
    # Código para verificar si una contingencia 
    # ya se encuentra registrada o no.
    session_start();

    # Verificar si algún administrador no
    # ha iniciado su correspondiente sesión.
    if(!isset($_SESSION["ID_administrador"])) {
        header("location: ../index.php");
        die();
    }

    if($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET["ID-colaborador"], $_GET["fecha-registro"])) {
        # Iniciar y verificar la conexión
        # con la base de datos.
        $conexion_base = new mysqli("localhost", "root", "", "checadorumd");
        if($conexion_base->connect_error) {
            echo "false";
            die();
        }

        # Verificar si la contingencia está registrado.
        if($colaborador = $conexion_base->query("SELECT * FROM contingencia WHERE 
        ID_colaborador = '" . $_GET["ID-colaborador"] . "' AND fecha = '" . $_GET["fecha-registro"] . "';")) {
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