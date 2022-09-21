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
        $colaborador = $conexion_base->query("SELECT colaborador.ID, colaborador.nombres, colaborador.apellido_paterno, 
        colaborador.apellido_materno, carrera.nombre AS carrera, modalidad_colaborador.nombre AS modalidad
        FROM colaborador JOIN carrera ON colaborador.ID_carrera = carrera.ID
        JOIN modalidad_colaborador ON colaborador.ID_modalidad = modalidad_colaborador.ID
        WHERE colaborador.ID = '" . $_POST["ID"] . "' LIMIT 1;");
        if(isset($colaborador) && $colaborador->num_rows > 0) {
            $datos_colaborador = $colaborador->fetch_row();

            # Verificar el tipo de chequeo.
            switch($_POST["chequeo"]) {
                case "entrada":
                    # Verificar si ya se había hecho el chequeo de entrada.
                    $verificacion_chequeo = $conexion_base->query("SELECT * FROM chequeo
                    WHERE ID_colaborador = '" . $_POST["ID"] . "' AND fecha_chequeo = '" . date("Y-m-d") . "';");

                    if(isset($verificacion_chequeo) && $verificacion_chequeo->num_rows > 0) {
                        $resultado = 3;
                    }
                    else {
                        # Registrar el chequeo de entrada.
                        $hora_registro = date("H:i:s");
                        
                        try {
                            if($conexion_base->query("INSERT INTO chequeo(fecha_chequeo, hora_inicial, ID_colaborador)
                            VALUES('" . date("Y-m-d") . "', '$hora_registro', '" . $_POST["ID"] . "');")) {
                                $resultado = 1;
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
                break;

                case "salida":
                    # Verificar si el chequeo de entrada
                    # del día actual ya fue realizado.
                    $verificacion_chequeo = $conexion_base->query("SELECT * FROM chequeo
                    WHERE ID_colaborador = '" . $_POST["ID"] . "' AND fecha_chequeo = '" . date("Y-m-d") . "' LIMIT 1;");
                    
                    if(isset($verificacion_chequeo) && $verificacion_chequeo->num_rows > 0) {
                        # Verificar si no se ha hecho el chequeo de salida.
                        if(is_null($verificacion_chequeo->fetch_row()[2])) {
                            try {
                                # Registrar el chequeo de salida.
                                $hora_chequeo = date("H:i:s");

                                if($conexion_base->query("UPDATE chequeo SET hora_final = '$hora_chequeo'
                                WHERE fecha_chequeo = '" . date("Y-m-d") . "' AND ID_colaborador = '" . $_POST["ID"] . "';")) {
                                    $resultado = 4;
                                } 
                                else {
                                    $resultado = 6;
                                }
                            }
                            catch(Exception $e) {
                                $resultado = 6;
                            }
                        }
                        else {
                            $resultado = 7;
                        }
                    }
                    else {
                        $resultado = 5;
                    }
                    @$verificacion_chequeo->close();


                    # Actualizar el chequeo del día actual.
                break;

                default:
                    header("location: ../index.php");
                    die();
                break;
            }
        }
        else {
            $resultado = 8;
        }
        @$colaborador->close();
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
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-gH2yIJqKdNHPEq0n4Mqa/HGKIhSkIHeL5AyhkYV8i59U5AR6csBvApHHNl/vI1Bx" crossorigin="anonymous">

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
        <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"> </script>
        <script type="text/javascript"> 
            let estadoChequeo = <?php echo $resultado?>;
            console.log(estadoChequeo);
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
                            title: 'Chequeo de entrada ya realizado',
                            html: <?php echo "\"<p class='mb-4'> El siguiente colaborador ya hizo el chequeo de entrada de la fecha actual (" . date("d-m-Y") . "). </p> \\n"
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
                            html: <?php echo "\"<p class='mb-4'> El siguiente colaborador no ha hecho el chequeo de entrada de la fecha actual (" . date("d-m-Y") . "). </p> \\n"
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
                            html: <?php echo "\"<p class='mb-4'> El siguiente colaborador ya hizo el chequeo de salida de la fecha actual (" . date("d-m-Y") . "). </p> \\n"
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
                            icon: 'error',
                            title: 'Error de autenticación',
                            text: 'El colaborador especificado es inexistente'
                        }).then((resultado) => {
                            location.href="../index.php";
                        });
                    });
                break;
            }
        </script>
    </body>
</html>