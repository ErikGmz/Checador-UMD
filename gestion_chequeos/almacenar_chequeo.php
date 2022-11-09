<?php
    # Verificar que se haya enviado un
    # formulario de registro de chequeo.
    if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST["chequeo"], $_POST["ID"])) {
        # Iniciar la conexión con la base de datos.
        $conexion_base = new mysqli("localhost", "root", "", "checadorumd");
        if($conexion_base->connect_error) {
            die("Hubo un error al conectar con la base de datos. " . $conexion_base->connect_error);
        }

        # Definir la zona horaria.
        date_default_timezone_set('America/Mexico_City');

        # Verificar si el colaborador indicado existe.
        try {
            $colaborador = $conexion_base->query("SELECT colaborador.ID, colaborador.nombres, colaborador.apellido_paterno, 
            colaborador.apellido_materno, carrera.nombre AS carrera, modalidad_colaborador.nombre AS modalidad,
            colaborador.numero_retardos, horario.hora_inicial FROM colaborador JOIN carrera ON colaborador.ID_carrera = carrera.ID
            JOIN modalidad_colaborador ON colaborador.ID_modalidad = modalidad_colaborador.ID
            JOIN horario ON colaborador.ID_horario = horario.ID
            WHERE colaborador.ID = '" . $_POST["ID"] . "' LIMIT 1;");
            if(isset($colaborador) && $colaborador->num_rows > 0) {
                $datos_colaborador = $colaborador->fetch_row();

                # Verificar el tipo de chequeo.
                switch($_POST["chequeo"]) {
                    case "entrada":
                        # Verificar si la fecha actual está en el
                        # rango permitido (01-01-2021 a 30-12-2030).
                        if(strtotime(date("Y-m-d")) >= strtotime("2021-01-01") &&
                        strtotime(date("Y-m-d")) <= strtotime("2030-12-30")) {
                            # Obtener el último chequeo que el colaborador
                            # realizó en la fecha correspondiente.
                            $verificacion_chequeo = $conexion_base->query("SELECT numero_chequeo, 
                            hora_final FROM chequeo WHERE fecha_chequeo = '" . date("Y-m-d") 
                            . "' AND ID_colaborador = '" . $_POST["ID"] 
                            . "' AND numero_chequeo = (SELECT MAX(numero_chequeo) FROM chequeo WHERE 
                            fecha_chequeo = '" . date("Y-m-d") . "' AND ID_colaborador = '" . $_POST["ID"] . "') LIMIT 1;");

                            if(isset($verificacion_chequeo) && $verificacion_chequeo->num_rows > 0) {
                                # Verificar si el último chequeo que el colaborador
                                # realizó en la fecha correspondiente fue completado.
                                $resultados = $verificacion_chequeo->fetch_row();
                                if(is_null($resultados[1])) {
                                    $resultado = 3;
                                }
                                else {
                                    # Obtener el número del siguiente chequeo del día.
                                    $numero_chequeo = (int)$resultados[0] + 1;
                                }
                            }
                            else {
                                $numero_chequeo = 1;
                            }

                            if(!isset($resultado)) {
                                # Registrar el chequeo de entrada.
                                $hora_registro = date("H:i:s");

                                try {
                                    if($conexion_base->query("INSERT INTO chequeo(numero_chequeo, fecha_chequeo, hora_inicial, ID_colaborador)
                                    VALUES('$numero_chequeo', '" . date("Y-m-d") . "', '$hora_registro', '" . $_POST["ID"] . "');")) {
                                        # Verificar si se llegó 15 minutos después de
                                        # la hora inicial (esquema de retardos) para el primer chequeo.
                                        if(strtotime("1970-01-01 $hora_registro UTC") - strtotime("1970-01-01 " . $datos_colaborador[7] . " UTC") 
                                        >= strtotime("1970-01-01 00:15:00 UTC") && $numero_chequeo == 1) {
                                            $incremento_retardos = (int)$datos_colaborador[6] + 1;

                                            # Actualizar los retardos en los datos del colaborador.
                                            if($conexion_base->query("UPDATE colaborador SET numero_retardos = '$incremento_retardos'
                                            WHERE ID = '" . $_POST["ID"] . "';")) {
                                                if($incremento_retardos <= 2) {
                                                    $resultado = 8;
                                                }
                                                else {
                                                    # Definir como bloqueado al chequeo.
                                                    if($conexion_base->query("UPDATE chequeo SET bloqueo_registro = '1' WHERE
                                                    fecha_chequeo = '" . date("Y-m-d") . "' AND ID_colaborador = '" . $_POST["ID"] . "'
                                                    AND numero_chequeo = $numero_chequeo;")) {
                                                        $resultado = 9;
                                                    }
                                                    else {
                                                        $resultado = 2;
                                                    }
                                                }
                                            }
                                            else {
                                                $resultado = 2;
                                            }
                                        }
                                        else {
                                            $incremento_retardos = (int)$datos_colaborador[6];
                                            if($incremento_retardos <= 2) {
                                                $resultado = 1;
                                            }
                                            else {
                                                # Definir como bloqueado al chequeo.
                                                if($conexion_base->query("UPDATE chequeo SET bloqueo_registro = '1' WHERE
                                                fecha_chequeo = '" . date("Y-m-d") . "' AND ID_colaborador = '" . $_POST["ID"] . "'
                                                AND numero_chequeo = $numero_chequeo;")) {
                                                    $resultado = 9;
                                                }
                                                else {
                                                    $resultado = 2;
                                                }
                                            }
                                        }
                                    } 
                                    else {
                                        $resultado = 2;
                                    }
                                }
                                catch(Exception $e) {
                                    $resultado = 2;
                                }
                            }
                            @$verificacion_chequeo->close();
                        }
                        else {
                            $resultado = 12;
                        }
                    break;

                    case "salida":
                        # Verificar si la fecha actual está en el
                        # rango permitido (01-01-2021 a 30-12-2030).
                        if(strtotime(date("Y-m-d")) >= strtotime("2021-01-01") &&
                        strtotime(date("Y-m-d")) <= strtotime("2030-12-30")) {
                            # Obtener el último chequeo que el colaborador
                            # realizó en la fecha correspondiente.
                            $verificacion_chequeo = $conexion_base->query("SELECT numero_chequeo, 
                            hora_final FROM chequeo WHERE fecha_chequeo = '" . date("Y-m-d") 
                            . "' AND ID_colaborador = '" . $_POST["ID"] 
                            . "' AND numero_chequeo = (SELECT MAX(numero_chequeo) FROM chequeo WHERE 
                            fecha_chequeo = '" . date("Y-m-d") . "' AND ID_colaborador = '" . $_POST["ID"] . "') LIMIT 1;");

                            if(isset($verificacion_chequeo) && $verificacion_chequeo->num_rows > 0) {
                                # Verificar si el último chequeo que el colaborador
                                # realizó en la fecha correspondiente fue completado.
                                $resultados = $verificacion_chequeo->fetch_row();
                                if(!is_null($resultados[1])) {
                                    $resultado = 7;
                                }
                                else {
                                    # Obtener el número del último chequeo del día.
                                    $numero_chequeo = (int)$resultados[0];
                                }
                            }
                            else {
                                $resultado = 5;
                            }

                            if(!isset($resultado)) {
                                try {
                                    # Registrar el chequeo de salida.
                                    $hora_chequeo = date("H:i:s");

                                    if($conexion_base->query("UPDATE chequeo SET hora_final = '$hora_chequeo'
                                    WHERE fecha_chequeo = '" . date("Y-m-d") . "' AND ID_colaborador = '" . $_POST["ID"] . "'
                                    AND numero_chequeo = $numero_chequeo;")) {
                                        # Verificar si los retardos del colaborador ya fueron excedidos.
                                        if((int)$datos_colaborador[6] > 2) {
                                            $resultado = 10;
                                        }  
                                        else {
                                            $resultado = 4;
                                        }
                                    } 
                                    else {
                                        $resultado = 6;
                                    }
                                }
                                catch(Exception $e) {
                                    $resultado = 6;
                                }
                            }
                            @$verificacion_chequeo->close();
                        }
                        else {
                            $resultado = 12;
                        }
                    break;

                    default:
                        header("location: ../index.php");
                        die();
                    break;
                }
            }
            else {
                $resultado = 11;
            }
        }
        catch(Exception $e) {
            $resultado = 13;
        }
        finally {
            # Cerrar la conexión con la base de datos.
            $conexion_base->close();
        }
    }
    else {
        header("location: ../index.php");
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
        <link rel="stylesheet" href="../css/bootstrap/bootstrap.min.css">

        <!--Título de la página-->
        <title> Resultado del chequeo </title>

        <!--Ícono de la página-->
        <link rel="apple-touch-icon" sizes="76x76" href="../favicon/apple-touch-icon.png">
        <link rel="icon" type="image/png" sizes="32x32" href="../favicon/favicon-32x32.png">
        <link rel="icon" type="image/png" sizes="16x16" href="../favicon/favicon-16x16.png">
        <link rel="manifest" href="../site.webmanifest">
        <link rel="mask-icon" href="../favicon/safari-pinned-tab.svg" color="#5bbad5">
        <meta name="msapplication-TileColor" content="#da532c">
        <meta name="theme-color" content="#ffffff">
    </head>

    <!--Cuerpo de la página-->
    <body>
        <!--Scripts de la página-->
        <script src="../js/sweetalert2/sweetalert2@11.js"> </script>
        <script type="text/javascript"> 
            let estadoChequeo = <?php echo $resultado?>;
            switch(estadoChequeo) {
                case 1:
                    window.addEventListener("load", () => {
                        Swal.fire({
                            icon: "success",
                            title: "Éxito de chequeo de entrada",
                            html: <?php echo "\"<p class='my-2'> <b> Colaborador: </b> " . @$datos_colaborador[1] . " " . @$datos_colaborador[2] . " " . @$datos_colaborador[3] . "</p> \\n"
                            . "<p class='mb-2'> <b> ID: </b> " . @$datos_colaborador[0] . "</p> \\n"
                            . "<p class='mb-0'> <b> Hora de chequeo de entrada: </b>" . date("h:i:s A", strtotime(@$hora_registro)) . " </p>\""
                            ?>
                        }).then((resultado) => {
                            location.href="../index.php";
                        });
                    });
                break;

                case 2:
                    window.addEventListener("load", () => {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error de chequeo de entrada',
                            html: <?php echo "\"<p class='mb-4'> Ocurrió un error al registrar el siguiente chequeo de entrada: </p> \\n"
                            . "<p class='mb-2'> <b> Colaborador: </b> " . @$datos_colaborador[1] . " " . @$datos_colaborador[2] . " " . @$datos_colaborador[3] . "</p> \\n"
                            . "<p class='mb-2'> <b> ID: </b> " . @$datos_colaborador[0] . "</p> \\n"
                            . "<p class='mb-0'> <b> Hora de chequeo de entrada: </b>" . date("h:i:s A", strtotime(@$hora_registro)) . " </p>\""
                            ?>
                        }).then((resultado) => {
                            location.href="../index.php";
                        });
                    });
                break;

                case 3:
                    window.addEventListener("load", () => {
                        Swal.fire({
                            icon: 'error',
                            title: 'Chequeo de salida sin realizar',
                            html: <?php echo "\"<p class='mb-4'> El siguiente colaborador no puede realizar otro chequeo de entrada hasta llevar a cabo uno de salida (" . date("d-m-Y") . "): </p> \\n"
                            . "<p class='mb-2'> <b> Colaborador: </b> " . @$datos_colaborador[1] . " " . @$datos_colaborador[2] . " " . @$datos_colaborador[3] . "</p> \\n"
                            . "<p class='mb-0'> <b> ID: </b> " . @$datos_colaborador[0] . "</p> \"" ?>
                        }).then((resultado) => {
                            location.href="../index.php";
                        });
                    });
                break;

                case 4:
                    window.addEventListener("load", () => {
                        Swal.fire({
                            icon: "success",
                            title: "Éxito de chequeo de salida",
                            html: <?php echo "\"<p class='my-2'> <b> Colaborador: </b> " . @$datos_colaborador[1] . " " . @$datos_colaborador[2] . " " . @$datos_colaborador[3] . "</p> \\n"
                            . "<p class='mb-2'> <b> ID: </b> " . @$datos_colaborador[0] . "</p> \\n"
                            . "<p class='mb-0'> <b> Hora de chequeo de salida: </b>" . date("h:i:s A", strtotime(@$hora_chequeo)) . " </p>\""
                            ?>
                        }).then((resultado) => {
                            location.href="../index.php";
                        });
                    });
                break;

                case 5:
                    window.addEventListener("load", () => {
                        Swal.fire({
                            icon: 'error',
                            title: 'Chequeo de entrada no realizado',
                            html: <?php echo "\"<p class='mb-4'> El siguiente colaborador no ha hecho un nuevo chequeo de entrada de la fecha actual (" . date("d-m-Y") . "): </p> \\n"
                            . "<p class='mb-2'> <b> Colaborador: </b> " . @$datos_colaborador[1] . " " . @$datos_colaborador[2] . " " . @$datos_colaborador[3] . "</p> \\n"
                            . "<p class='mb-0'> <b> ID: </b> " . @$datos_colaborador[0] . "</p> \"" ?>
                        }).then((resultado) => {
                            location.href="../index.php";
                        });
                    });
                break;

                case 6:
                    window.addEventListener("load", () => {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error de chequeo de salida',
                            html: <?php echo "\"<p class='mb-4'> Ocurrió un error al registrar el siguiente chequeo de salida: </p> \\n"
                            . "<p class='mb-2'> <b> Colaborador: </b> " . @$datos_colaborador[1] . " " . @$datos_colaborador[2] . " " . @$datos_colaborador[3] . "</p> \\n"
                            . "<p class='mb-2'> <b> ID: </b> " . @$datos_colaborador[0] . "</p> \\n"
                            . "<p class='mb-0'> <b> Hora de chequeo de salida: </b>" . date("h:i:s A", strtotime(@$hora_chequeo)) . " </p>\""
                            ?>
                        }).then((resultado) => {
                            location.href="../index.php";
                        });
                    });
                break;

                case 7:
                    window.addEventListener("load", () => {
                        Swal.fire({
                            icon: 'error',
                            title: 'Chequeo de salida ya realizado',
                            html: <?php echo "\"<p class='mb-4'> El siguiente colaborador ya hizo el chequeo de salida del último registro de la fecha actual (" . date("d-m-Y") . "): </p> \\n"
                            . "<p class='mb-2'> <b> Colaborador: </b> " . @$datos_colaborador[1] . " " . @$datos_colaborador[2] . " " . @$datos_colaborador[3] . "</p> \\n"
                            . "<p class='mb-0'> <b> ID: </b> " . @$datos_colaborador[0] . "</p> \"" ?>
                        }).then((resultado) => {
                            location.href="../index.php";
                        });
                    });
                break;

                case 8:
                    window.addEventListener("load", () => {
                        Swal.fire({
                            icon: "warning",
                            title: "Éxito de chequeo de entrada con retardo",
                            html: <?php echo "\"<p class='mb-4'> El siguiente colaborador ya acumuló " . @$incremento_retardos . " retardo" . (((int)@$incremento_retardos > 1) ? "s;" : ";")
                            . " el máximo permitido es de 2. Chequeo del día " . date("d-m-Y") . ": </p> \\n"
                            . "<p class='my-2'> <b> Colaborador: </b> " . @$datos_colaborador[1] . " " . @$datos_colaborador[2] . " " . @$datos_colaborador[3] . "</p> \\n"
                            . "<p class='mb-2'> <b> ID: </b> " . @$datos_colaborador[0] . "</p> \\n"
                            . "<p class='mb-0'> <b> Hora de chequeo de entrada: </b>" . date("h:i:s A", strtotime(@$hora_registro)) . " </p>\""
                            ?>
                        }).then((resultado) => {
                            location.href="../index.php";
                        });
                    });
                break;

                case 9:
                    window.addEventListener("load", () => {
                        Swal.fire({
                            icon: "warning",
                            title: "Éxito de chequeo de entrada con retardos excedidos",
                            html: <?php echo "\"<p class='mb-4'> El siguiente colaborador ya acumuló " . @$incremento_retardos . " retardo" . (((int)@$incremento_retardos > 1) ? "s" : "") .
                            ", excediendo los 2 permitidos, debido a lo cual los próximos chequeos se bloquearán. Para solucionar el problema se debe hablar con algún coordinador. Chequeo del día " . date("d-m-Y") . ": </p> \\n"
                            . "<p class='my-2'> <b> Colaborador: </b> " . @$datos_colaborador[1] . " " . @$datos_colaborador[2] . " " . @$datos_colaborador[3] . "</p> \\n"
                            . "<p class='mb-2'> <b> ID: </b> " . @$datos_colaborador[0] . "</p> \\n"
                            . "<p class='mb-0'> <b> Hora de chequeo de entrada: </b>" . date("h:i:s A", strtotime(@$hora_registro)) . " </p>\""
                            ?>
                        }).then((resultado) => {
                            location.href="../index.php";
                        });
                    });
                break;

                case 10:
                    window.addEventListener("load", () => {
                        Swal.fire({
                            icon: "warning",
                            title: "Éxito de chequeo de salida con retardos excedidos",
                            html: <?php echo "\"<p class='mb-4'> El siguiente colaborador ha excedido los dos retardos permitidos, debido a lo cual los chequeos posteriores se han bloqueado. Chequeo del día " . date("d-m-Y") . ": </p> \\n"
                            . "<p class='my-2'> <b> Colaborador: </b> " . @$datos_colaborador[1] . " " . @$datos_colaborador[2] . " " . @$datos_colaborador[3] . "</p> \\n"
                            . "<p class='mb-2'> <b> ID: </b> " . @$datos_colaborador[0] . "</p> \\n"
                            . "<p class='mb-0'> <b> Hora de chequeo de salida: </b>" . date("h:i:s A", strtotime(@$hora_chequeo)) . " </p>\""
                            ?>
                        }).then((resultado) => {
                            location.href="../index.php";
                        });
                    });
                break;

                case 11:
                    window.addEventListener("load", () => {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error de autenticación',
                            text: 'El colaborador especificado es inexistente'
                        }).then((resultado) => {
                            location.href="../index.php";
                        });
                    });
                break;

                case 12:
                    window.addEventListener("load", () => {
                        Swal.fire({
                            icon: "error",
                            title: "Fecha de chequeo no válida",
                            text: "La fecha de chequeo no corresponde al rango de fechas permitido (01-01-2021 al 30-12-2030)"
                        }).then((resultado) => {
                            location.href="../index.php";
                        });
                    });
                break;

                default:
                    window.addEventListener("load", () => {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error desconocido',
                            text: 'Ocurrió un error al realizar el chequeo'
                        }).then((resultado) => {
                            location.href="../index.php";
                        });
                    });
                break;
            }
        </script>
    </body>
</html>