<?php
    # Código para verificar si el ID de un
    # administrador ya se encuentra registrado o no.
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

        # Obtener los números de chequeos correspondientes al colaborador
        # y fechas especificados en el formulario de modificación de chequeo.
        if($chequeos = $conexion_base->query("SELECT numero_chequeo FROM chequeo WHERE fecha_chequeo = '"
        . $_GET["fecha-chequeo"] . "' AND ID_colaborador = " . $_GET["ID-colaborador"] . " ORDER BY numero_chequeo ASC;")) {
            if($chequeos->num_rows > 0) {
                while($resultados = $chequeos->fetch_row()) {
                    echo $resultados[0] . " - ";
                }
            }
            else {
                echo "";
            }
        }
        else {
            echo "";
        }

        # Cerrar la conexión con la base de datos.
        $conexion_base->close();
    }
    else {
        header("location: ../administrador/menu_principal/menu_administrador.php");
        die();
    }
?>