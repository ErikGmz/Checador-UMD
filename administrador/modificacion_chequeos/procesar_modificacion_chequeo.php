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
    if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST["ID-colaborador"], $_POST["fecha-chequeo"], $_POST["fecha-anterior"],
    $_POST["hora-inicial"], $_POST["estado-chequeo"],  $_POST["anterior-ID-colaborador"])) {
        # Iniciar y verificar la conexión
        # con la base de datos.
        $conexion_base = new mysqli("localhost", "root", "", "checadorumd");
        if($conexion_base->connect_error) {
            die("Hubo un error al conectar con la base de datos. " . $conexion_base->connect_error);
        }

        # Verificar si los datos especificados no corresponden
        # a un chequeo ya existente en el sistema.
        try {
            if($resultados = $conexion_base->query("SELECT * FROM chequeo WHERE 
            ID_colaborador = '" . $_POST["ID-colaborador"] . "' AND fecha_chequeo = '" . $_POST["fecha-chequeo"] . "';")) {
                if($resultados->num_rows > 0 && $_POST["ID-colaborador"] != $_POST["anterior-ID-colaborador"] 
                && $_POST["fecha-anterior"] != $_POST["fecha-chequeo"]) {
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
                        if(strtotime($_POST["fecha-chequeo"]) >= strtotime("2021-01-01") &&
                        strtotime($_POST["fecha-chequeo"]) <= strtotime("2030-12-30")) {
                            # Actualizar el chequeo de la base de datos.
                            try {
                                if($tiempo_final != "") {
                                    $registro_hora_final = "'" . date("H:i:s", strtotime($tiempo_final)) . "'";
                                }
                                else {
                                    $registro_hora_final = "NULL";
                                }
                                echo $registro_hora_final;

                                echo "UPDATE chequeo SET fecha_chequeo = '" . $_POST["fecha-chequeo"] 
                                . "', hora_inicial = '" . date("H:i:s", strtotime($tiempo_inicial)) . "', hora_final = $registro_hora_final, "
                                . " bloqueo_registro = '" . $_POST["estado-chequeo"] . "', ID_colaborador = '" . $_POST["ID-colaborador"] 
                                . "' WHERE ID_colaborador = '" . $_POST["anterior-ID-colaborador"] . "' AND fecha_chequeo = '" . $_POST["fecha-anterior"] . "'";
                                if($conexion_base->query("UPDATE chequeo SET fecha_chequeo = '" . $_POST["fecha-chequeo"] 
                                . "', hora_inicial = '" . date("H:i:s", strtotime($tiempo_inicial)) . "', hora_final = $registro_hora_final, "
                                . " bloqueo_registro = '" . $_POST["estado-chequeo"] . "', ID_colaborador = '" . $_POST["ID-colaborador"] 
                                . "' WHERE ID_colaborador = '" . $_POST["anterior-ID-colaborador"] . "' AND fecha_chequeo = '" . $_POST["fecha-anterior"] . "'")) {
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
                $resultados->close();
            }
            else {
                $resultado = 1;
            }   
        }
        catch(Exception $e) {
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
                            html: <?php echo "\"<p class='mb-4'> El siguiente chequeo es inexistente en el sistema: </p> \\n"
                            . "<p class='my-2'> <b> Colaborador: </b> " . @$_POST["ID-colaborador"] . " </p> \\n"
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
                            text: "La fecha de chequeo no corresponde al rango de fechas permitido"
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