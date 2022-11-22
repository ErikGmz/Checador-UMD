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

    if($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET["ID-colaborador"]
    , $_GET["fecha-chequeo"], $_GET["tipo-operacion"], $_GET["numero-chequeo"])) {
        # Iniciar y verificar la conexión
        # con la base de datos.
        $conexion_base = new mysqli("localhost", "root", "", "checadorumd");
        if($conexion_base->connect_error) {
            echo "false";
            die();
        }

        if($_GET["tipo-operacion"] == 1) {
            # Verificar si existe al menos un chequeo que el
            # colaborador haya llevado a cabo en la fecha actual.
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
                        echo "false - " . $resultados[1] . " - " . $resultados[2];
                    }
                }
                else {
                    echo "false";
                }
            }
            else {
                echo "false";
            }
        }
        else {
            $datos_chequeo_anterior = "";
            $datos_chequeo_actual = "";
            $datos_chequeo_posterior = "";

            # Verificar si existe un chequeo anterior al especificado.
            if($chequeo = $conexion_base->query("CALL obtener_chequeo_anterior('" . $_GET["fecha-chequeo"] 
            . "', " . $_GET["ID-colaborador"] . ", " . $_GET["numero-chequeo"] . ")")) {
                do {
                    if($auxiliar = $conexion_base->store_result()) {
                        $auxiliar->free();
                    }
                } while($conexion_base->more_results() && $conexion_base->next_result());

                if($chequeo->num_rows > 0) {
                    $resultados = $chequeo->fetch_row();

                    # Comprobar si la hora final de dicho chequeo existe.
                    if(is_null($resultados[0])) {
                        $datos_chequeo_anterior = " - ";
                    }
                    else {
                        $datos_chequeo_anterior = $resultados[0] . " - ";
                    }

                    # Comprobar si la hora inicial de dicho chequeo existe.
                    if(is_null($resultados[1])) {
                        $datos_chequeo_anterior .= " - ";
                    }
                    else {
                        $datos_chequeo_anterior .= $resultados[1] . " - ";
                    }
                }
                else {
                    $datos_chequeo_anterior = " - - ";
                }
            }
            else {
                $datos_chequeo_anterior = " - - ";
            }

            # Verificar si existe el chequeo especificado.
            if($chequeo = $conexion_base->query("CALL obtener_chequeo('" . $_GET["fecha-chequeo"] 
            . "', " . $_GET["ID-colaborador"] . ", " . $_GET["numero-chequeo"] . ")")) {
                do {
                    if($auxiliar = $conexion_base->store_result()) {
                        $auxiliar->free();
                    }
                } while($conexion_base->more_results() && $conexion_base->next_result());

                if($chequeo->num_rows > 0) {
                    $resultados = $chequeo->fetch_row();

                    # Comprobar si la hora final de dicho chequeo existe.
                    if(is_null($resultados[0])) {
                        $datos_chequeo_actual = " - ";
                    }
                    else {
                        $datos_chequeo_actual = $resultados[0] . " - ";
                    }

                    # Comprobar si la hora inicial de dicho chequeo existe.
                    if(is_null($resultados[1])) {
                        $datos_chequeo_actual .= " - ";
                    }
                    else {
                        $datos_chequeo_actual .= $resultados[1] . " - ";
                    }
                }
                else {
                    $datos_chequeo_actual = " - - ";
                }
            }
            else {
                $datos_chequeo_actual = " - - ";
            }

            # Verificar si existe un chequeo posterior al especificado.
            if($chequeo = $conexion_base->query("CALL obtener_chequeo_posterior('" . $_GET["fecha-chequeo"] 
            . "', " . $_GET["ID-colaborador"] . ", " . $_GET["numero-chequeo"] . ")")) {
                do {
                    if($auxiliar = $conexion_base->store_result()) {
                        $auxiliar->free();
                    }
                } while($conexion_base->more_results() && $conexion_base->next_result());

                if($chequeo->num_rows > 0) {
                    $resultados = $chequeo->fetch_row();

                    # Comprobar si la hora final de dicho chequeo existe.
                    if(is_null($resultados[0])) {
                        $datos_chequeo_posterior = " - ";
                    }
                    else {
                        $datos_chequeo_posterior = $resultados[0] . " - ";
                    }

                    # Comprobar si la hora inicial de dicho chequeo existe.
                    if(!is_null($resultados[1])) {
                        $datos_chequeo_posterior .= $resultados[1];
                    }
                }
                else {
                    $datos_chequeo_posterior = " - ";
                }
            }
            else {
                $datos_chequeo_posterior = " - ";
            }
        }

        # Comprobar los datos completos del chequeo especificado.
        if($datos_chequeo_anterior == " - - " && $datos_chequeo_actual == " - - " && $datos_chequeo_posterior == " - ") {
            echo "true";
        }
        else {
            echo "false - " . $datos_chequeo_anterior . $datos_chequeo_actual . $datos_chequeo_posterior;
        }
        # Cerrar la conexión con la base de datos.
        $conexion_base->close();
    }
    else {
        header("location: ../administrador/menu_principal/menu_administrador.php");
        die();
    }
?>