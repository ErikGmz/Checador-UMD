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
    # formulario de modificación de contingencia.
    if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST["ID-colaborador"], $_POST["fecha-registro"], $_POST["fecha-anterior"],
    $_POST["hora-inicial"], $_POST["hora-final"], $_POST["observaciones"], $_POST["anterior-ID-colaborador"])) {
        # Iniciar y verificar la conexión
        # con la base de datos.
        $conexion_base = new mysqli("localhost", "root", "", "checadorumd");
        if($conexion_base->connect_error) {
            die("Hubo un error al conectar con la base de datos. " . $conexion_base->connect_error);
        }

        # Verificar si los datos especificados no corresponden
        # a otra contingencia ya existente en el sistema.
        try {
            if($resultados = $conexion_base->query("SELECT * FROM contingencia WHERE 
            ID_colaborador = '" . $_POST["ID-colaborador"] . "' AND fecha = '" . $_POST["fecha-registro"] . "';")) {
                if($resultados->num_rows > 0 && $_POST["ID-colaborador"] != $_POST["anterior-ID-colaborador"] 
                && $_POST["fecha-anterior"] != $_POST["fecha-registro"]) {
                    $resultado = 2;
                }
                else {
                    $tiempo_inicial = date("1970-01-01 " . $_POST["hora-inicial"] . ":00");
                    $tiempo_final = date("1970-01-01 " . $_POST["hora-final"] . ":00");
                    if($tiempo_final <= $tiempo_inicial || $tiempo_inicial > date("1970-01-01 21:00")
                    || $tiempo_inicial < date("1970-01-01 08:00") || $tiempo_final < date("1970-01-01 08:00")
                    || $tiempo_final > date("1970-01-01 21:00")) {
                        $resultado = 3;
                    }
                    else {
                        if(strtotime($_POST["fecha-registro"]) >= strtotime("2021-01-01") &&
                        strtotime($_POST["fecha-registro"]) <= strtotime("2030-12-30")) {
                            # Actualizar la contingencia en la base de datos.
                            try {
                                if($conexion_base->query("UPDATE contingencia SET fecha = '" . $_POST["fecha-registro"] . "', hora_inicial = '"
                                . date("H:i:s", strtotime($tiempo_inicial)) . "', hora_final = '" . date("H:i:s", strtotime($tiempo_final)) . "', 
                                observaciones = '" . $_POST["observaciones"] . "', ID_colaborador = '" . $_POST["ID-colaborador"] . "' WHERE
                                ID_colaborador = '" . $_POST["anterior-ID-colaborador"] . "' AND fecha = '" . $_POST["fecha-anterior"] . "';"))  {
                                    $resultado = 5;
                                }
                                else {
                                    $resultado = 1;
                                }
                            }
                            catch(Exception $e) {
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
        <title> Resultado de la modificación de la contingencia </title>

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
                            title: "Modificación no exitosa de contingencia",
                            text: "Ocurrió un error al tratar de modificar la contingencia"
                        }).then((resultado) => {
                            location.href="modificacion_contingencia.php";
                        });
                    });
                break;

                case 2:
                    window.addEventListener("load", () => {
                        Swal.fire({
                            icon: "error",
                            title: "Contingencia inexistente",
                            html: <?php echo "\"<p class='mb-4'> La siguiente contingencia es inexistente en el sistema: </p> \\n"
                            . "<p class='my-2'> <b> Colaborador: </b> " . @$_POST["ID-colaborador"] . " </p> \\n"
                            . "<p class='mb-0'> <b> Fecha de registro: </b> " . date("d-m-Y", strtotime(@$_POST["fecha-registro"])). "</p>\""
                            ?>
                        }).then((resultado) => {
                            location.href="modificacion_contingencia.php";
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
                            location.href="modificacion_contingencia.php";
                        });
                    });
                break;

                case 4:
                    window.addEventListener("load", () => {
                        Swal.fire({
                            icon: "error",
                            title: "Fecha de registro no válida",
                            text: "La fecha de registro no corresponde al rango de fechas permitido"
                        }).then((resultado) => {
                            location.href="modificacion_contingencia.php";
                        });
                    });
                break;

                case 5:
                    window.addEventListener("load", () => {
                        Swal.fire({
                            icon: "success",
                            title: "Modificación exitosa de contingencia",
                            html: <?php echo "\"<p class='mb-4'> La siguiente contingencia fue exitosamente modificada en el sistema: </p> \\n"
                            . "<p class='my-2'> <b> Colaborador: </b> " . @$_POST["ID-colaborador"] . " </p> \\n"
                            . "<p class='mb-0'> <b> Fecha de registro: </b> " . date("d-m-Y", strtotime(@$_POST["fecha-registro"])). "</p>\""
                            ?>
                        }).then((resultado) => {
                            location.href="modificacion_contingencia.php";
                        });
                    });
                break;

                default:
                    window.addEventListener("load", () => {
                        Swal.fire({
                            icon: "error",
                            title: "Error desconocido",
                            text: "Ocurrió un error al tratar de modificar la contingencia"
                        }).then((resultado) => {
                            location.href="modificacion_contingencia.php";
                        });
                    });
                break;
            }
        </script>
    </body>
</html>