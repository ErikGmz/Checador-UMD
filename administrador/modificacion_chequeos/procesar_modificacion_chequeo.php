<?php
    session_start();

    # Verificar si algún administrador ya
    # inició su correspondiente sesión.
    if(!isset($_SESSION["ID_administrador"])) {
        header("location: ../menu_principal/menu_administrador.php");
        die();
    }

    # Definir la zona horaria.
    date_default_timezone_set('America/Mexico_City');

    # Verificar que se haya enviado un
    # formulario de modificación de chequeo.
    if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST["ID-colaborador"], $_POST["fecha-chequeo"],
    $_POST["hora-inicial"], $_POST["hora-final"], $_POST["estado-chequeo"],  $_POST["numero-chequeo"])) {
        # Iniciar y verificar la conexión
        # con la base de datos.
        $conexion_base = new mysqli("localhost", "root", "", "checadorumd");
        if($conexion_base->connect_error) {
            die("Hubo un error al conectar con la base de datos. " . $conexion_base->connect_error);
        }

        # Verificar si los datos especificados corresponden
        # a un chequeo ya existente en el sistema.
        try {
            if($resultados = $conexion_base->query("SELECT * FROM chequeo WHERE 
            ID_colaborador = '" . $_POST["ID-colaborador"] . "' AND fecha_chequeo = '" . $_POST["fecha-chequeo"] . "'
            AND numero_chequeo = " . $_POST["numero-chequeo"] . ";")) {
                if($resultados->num_rows < 0) {
                    $resultado = 2;
                }
                else {
                    $tiempo_inicial = date("1970-01-01 " . $_POST["hora-inicial"]);

                    if(isset($_POST["hora-final"]) && @$_POST["hora-final"] != "") {
                        $tiempo_final = date("1970-01-01 " . $_POST["hora-final"]);
                    }
                    else {
                        $tiempo_final = "";
                    }

                    if(($tiempo_final <= $tiempo_inicial || $tiempo_inicial > date("1970-01-02 00:00")
                    || $tiempo_inicial < date("1970-01-01 00:00") || $tiempo_final < date("1970-01-01 00:00")
                    || $tiempo_final > date("1970-01-02 00:00")) && $tiempo_final != "") {
                        $resultado = 3;
                    }
                    else {
                        $hora_inicial_anterior = "";
                        $hora_final_anterior = "";
                        $hora_inicial_posterior = "";
                        $hora_final_posterior = "";

                        # Obtener los horarios del chequeo anterior.
                        if($resultados = $conexion_base->query("CALL obtener_chequeo_anterior('" . $_POST["fecha-chequeo"] 
                        . "', " . $_POST["ID-colaborador"] . ", " . $_POST["numero-chequeo"] . ")")) {
                            do {
                                if($auxiliar = $conexion_base->store_result()) {
                                    $auxiliar->free();
                                }
                            } while($conexion_base->more_results() && $conexion_base->next_result());

                            if($resultados->num_rows > 0) {
                                $registro = $resultados->fetch_row();

                                if(!is_null($registro[1])) {
                                    $hora_inicial_anterior = date("1970-01-01 " . $registro[1]);
                                }

                                if(!is_null($registro[0])) {
                                    $hora_final_anterior = date("1970-01-01 " . $registro[0]);
                                }
                            }
                        }

                        # Obtener los horarios del chequeo posterior.
                        if($resultados = $conexion_base->query("CALL obtener_chequeo_posterior('" . $_POST["fecha-chequeo"] 
                        . "', " . $_POST["ID-colaborador"] . ", " . $_POST["numero-chequeo"] . ")")) {
                            do {
                                if($auxiliar = $conexion_base->store_result()) {
                                    $auxiliar->free();
                                }
                            } while($conexion_base->more_results() && $conexion_base->next_result());

                            if($resultados->num_rows > 0) {
                                $registro = $resultados->fetch_row();

                                if(!is_null($registro[1])) {
                                    $hora_inicial_posterior = date("1970-01-01 " . $registro[1]);
                                }

                                if(!is_null($registro[0])) {
                                    $hora_final_posterior = date("1970-01-01 " . $registro[0]);
                                }
                            }
                        }

                        # Verificar si no hay conflictos con el horario del chequeo anterior.
                        if($hora_inicial_anterior != "") {
                            if($hora_inicial_anterior > $tiempo_inicial) {
                                $resultado = 6;
                            }
                        }

                        if($hora_final_anterior != "") {
                            if($hora_final_anterior > $tiempo_inicial) {
                                $resultado = 6;
                            }
                        }

                        # Verificar si no hay conflictos con el horario del chequeo posterior.
                        if($hora_inicial_posterior != "") {
                            if($hora_inicial_posterior < $tiempo_final) {
                                $resultado = 6;
                            }
                        }

                        if($hora_final_posterior != "") {
                            if($hora_final_posterior < $tiempo_final) {
                                $resultado = 6;
                            }
                        }

                        if(!isset($resultado)) {
                            if(strtotime($_POST["fecha-chequeo"]) >= strtotime("2021-01-01")) {
                                # Actualizar el chequeo de la base de datos.
                                try {
                                    if($tiempo_final != "") {
                                        $registro_hora_final = "'" . date("H:i:s", strtotime($tiempo_final)) . "'";
                                    }
                                    else {
                                        $registro_hora_final = "NULL";
                                    }

                                    if($conexion_base->query("UPDATE chequeo SET hora_inicial = '" . date("H:i:s", strtotime($tiempo_inicial)) 
                                    . "', hora_final = $registro_hora_final, bloqueo_registro = '" . $_POST["estado-chequeo"] 
                                    . "' WHERE ID_colaborador = '" . $_POST["ID-colaborador"] . "' AND fecha_chequeo = '" . $_POST["fecha-chequeo"] 
                                    . "' AND numero_chequeo = " . $_POST["numero-chequeo"] . ";")) {
                                        $conexion_base->query("CALL corregir_enumeracion_chequeos('" . $_POST["fecha-chequeo"] . "', " . $_POST["ID-colaborador"] . ");");
                                        do {
                                            if($auxiliar = $conexion_base->store_result()) {
                                                $auxiliar->free();
                                            }
                                        } while($conexion_base->more_results() && $conexion_base->next_result());

                                        $resultado = 5;
                                    }
                                    else {
                                        $resultado = 1;
                                    }
                                }
                                catch(Exception $e) {
                                    echo $e->getMessage();
                                    $resultado = 1;
                                }
                            }
                            else {
                                $resultado = 4;
                            }
                        }
                    }
                }
                $resultados->close();
            }
            else {
                $resultado = 1;
            }   
        }
        catch(Exception $e) { 
            echo $e->getMessage();
            $resultado = 1;
        }
        finally {
            # Cerrar la conexión con la base de datos.
            $conexion_base->close();
        }
        
    }
    else {
        header("location: ../menu_principal/menu_administrador.php");
        die();
    }
?>

<!--Código HTML del archivo-->
<html lang="es">
    <!--Cabecera de la página-->
    <head>
        <!--Metadatos de la página-->
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">

        <!--Estilos de la página-->
        <link rel="stylesheet" href="../../css/bootstrap/bootstrap.min.css">

        <!--Título de la página-->
        <title> Resultado de la modificación del chequeo </title>

        <!--Ícono de la página-->
        <link rel="apple-touch-icon" sizes="76x76" href="../../favicon/apple-touch-icon.png">
        <link rel="icon" type="image/png" sizes="32x32" href="../../favicon/favicon-32x32.png">
        <link rel="icon" type="image/png" sizes="16x16" href="../../favicon/favicon-16x16.png">
        <link rel="manifest" href="../../site.webmanifest">
        <link rel="mask-icon" href="../../favicon/safari-pinned-tab.svg" color="#5bbad5">
        <meta name="msapplication-TileColor" content="#da532c">
        <meta name="theme-color" content="#ffffff">
    </head>

    <!--Cuerpo de la página-->
    <body>
        <!--Scripts de la página-->
        <script src="../../js/sweetalert2/sweetalert2@11.js"> </script>
        <script type="text/javascript"> 
            let estadoInicioSesion = <?php echo $resultado?>;
            switch(estadoInicioSesion) {
                case 1:
                    window.addEventListener("load", () => {
                        Swal.fire({
                            icon: "error",
                            title: "Modificación no exitosa de chequeo",
                            text: "Ocurrió un error al tratar de modificar el chequeo"
                        }).then((resultado) => {
                            location.href="modificacion_chequeo.php";
                        });
                    });
                break;

                case 2:
                    window.addEventListener("load", () => {
                        Swal.fire({
                            icon: "error",
                            title: "Chequeo inexistente",
                            html: <?php echo "\"<p class='mb-4'> El siguiente chequeo no existe en el sistema: </p> \\n"
                            . "<p class='my-2'> <b> Colaborador: </b> " . @$_POST["ID-colaborador"] . " </p> \\n"
                            . "<p class='my-2'> <b> Número de chequeo: </b> " . @$_POST["numero-chequeo"] . " </p> \\n"
                            . "<p class='mb-0'> <b> Fecha de chequeo: </b> " . date("d-m-Y", strtotime(@$_POST["fecha-chequeo"])). "</p>\""
                            ?>
                        }).then((resultado) => {
                            location.href="modificacion_chequeo.php";
                        });
                    });
                break;

                case 3:
                    window.addEventListener("load", () => {
                        Swal.fire({
                            icon: "error",
                            title: "Rango de horario incorrecto",
                            text: "Las horas inicial y final indicadas no son válidas"
                        }).then((resultado) => {
                            location.href="modificacion_chequeo.php";
                        });
                    });
                break;

                case 4:
                    window.addEventListener("load", () => {
                        Swal.fire({
                            icon: "error",
                            title: "Fecha de chequeo no válida",
                            text: "La fecha de chequeo no corresponde al rango de fechas permitido (01-01-2021 o mayor)"
                        }).then((resultado) => {
                            location.href="modificacion_chequeo.php";
                        });
                    });
                break;

                case 5:
                    window.addEventListener("load", () => {
                        Swal.fire({
                            icon: "success",
                            title: "Modificación exitosa de chequeo",
                            html: <?php echo "\"<p class='mb-4'> El siguiente chequeo fue exitosamente modificado en el sistema: </p> \\n"
                            . "<p class='my-2'> <b> Colaborador: </b> " . @$_POST["ID-colaborador"] . " </p> \\n"
                            . "<p class='my-2'> <b> Número de chequeo: </b> " . @$_POST["numero-chequeo"] . " </p> \\n"
                            . "<p class='mb-0'> <b> Fecha de chequeo: </b> " . date("d-m-Y", strtotime(@$_POST["fecha-chequeo"])). "</p>\""
                            ?>
                        }).then((resultado) => {
                            location.href="modificacion_chequeo.php";
                        });
                    });
                break;

                case 6:
                    window.addEventListener("load", () => {
                        Swal.fire({
                            icon: "error",
                            title: "Inconsistencias entre los horarios del chequeo actualizado y los otros de la fecha correspondiente",
                            html: <?php echo "\"<p class='mb-4'> Datos del chequeo: </p> \\n"
                            . "<p class='my-2'> <b> Colaborador: </b> " . @$_POST["ID-colaborador"] . " </p> \\n"
                            . "<p class='my-2'> <b> Número de chequeo: </b> " . @$_POST["numero-chequeo"] . " </p> \\n"
                            . "<p class='mb-0'> <b> Fecha de chequeo: </b> " . date("d-m-Y", strtotime(@$_POST["fecha-chequeo"])). "</p>\""
                            ?>
                        }).then((resultado) => {
                            location.href="modificacion_chequeo.php";
                        });
                    });
                break;

                default:
                    window.addEventListener("load", () => {
                        Swal.fire({
                            icon: "error",
                            title: "Error desconocido",
                            text: "Ocurrió un error al tratar de modificar el chequeo"
                        }).then((resultado) => {
                            location.href="modificacion_chequeo.php";
                        });
                    });
                break;
            }
        </script>
    </body>
</html>