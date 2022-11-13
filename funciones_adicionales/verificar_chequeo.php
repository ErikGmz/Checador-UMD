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

        # Verificar si existe al menos un chequeo que el colaborador
        # haya llevado a cabo en la fecha actual.
        if($chequeo = $conexion_base->query("CALL obtener_ultimo_chequeo('" . $_GET["fecha-chequeo"] 
        . "', " . $_GET["ID-colaborador"] . ")")) {
            do {
                if($auxiliar = $conexion_base->store_result()) {
                    $auxiliar->free();
                }
            } while($conexion_base->more_results() && $conexion_base->next_result());

            if($chequeo->num_rows > 0) {
                $resultados = $chequeo->fetch_row();

                # Comprobar si dicho chequeo fue realmente completado.
                if(is_null($resultados[1])) {
                    echo "true";
                }
                else {
                    echo "false - " . $resultados[1];
                }
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