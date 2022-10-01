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
    # formulario de adición de colaborador.
    if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST["ID-colaborador"], $_POST["primer-nombre"], 
    $_POST["primer-apellido"], $_POST["carrera"], $_POST["modalidad"], $_POST["hora-entrada"], $_POST["hora-salida"])) {
        # Iniciar y verificar la conexión
        # con la base de datos.
        $conexion_base = new mysqli("localhost", "root", "", "checadorumd");
        if($conexion_base->connect_error) {
            die("Hubo un error al conectar con la base de datos. " . $conexion_base->connect_error);
        }

        # Verificar si el ID especificado no corresponde
        # a un colaborador ya existente en el sistema.
        try {
            if($resultados = $conexion_base->query("SELECT * FROM colaborador WHERE ID = '" 
            . $_POST["ID-colaborador"] . "';")) {
                if($resultados->num_rows > 0) {
                    $resultado = 2;
                }
                else {
                    $tiempo_entrada = date("1970-01-01 " . $_POST["hora-entrada"] . ":00");
                    $tiempo_salida = date("1970-01-01 " . $_POST["hora-salida"] . ":00");
                    if($tiempo_salida <= $tiempo_entrada || $tiempo_entrada > date("1970-01-01 21:00")
                    || $tiempo_entrada < date("1970-01-01 08:00") || $tiempo_salida < date("1970-01-01 08:00")
                    || $tiempo_salida > date("1970-01-01 21:00")) {
                        $resultado = 3;
                    }
                    else {
                        # Eliminar los espacios en los extremos
                        # de cada uno de los nombres del colaborador.
                        if(isset($_POST["segundo-apellido"])) $segundo_nombre = trim($_POST["segundo-nombre"]);
                        else $segundo_nombre= "";
                        $nombres = ucwords(strtolower(trim($_POST["primer-nombre"]))) . " " . ucwords(strtolower(trim($segundo_nombre)));

                        $apellido_paterno = ucwords(strtolower(trim($_POST["primer-apellido"])));
                        if(isset($_POST["segundo-apellido"])) $apellido_materno = ucwords(strtolower(trim($_POST["segundo-apellido"])));
                        else $apellido_materno = "";

                        # Verificar si el horario introducido ya existe en la base de datos.
                        if($horarios = $conexion_base->query("SELECT ID from horario WHERE hora_inicial = 
                        '" . $_POST["hora-entrada"] . "' AND hora_final = '" . $_POST["hora-salida"] . "';")) {
                            $conexion_base->query("START TRANSACTION;");

                            if($horarios->num_rows > 0) {
                                $ID_horario = $horarios->fetch_row()[0];
                            }
                            else {
                                # Agregar a la base de datos el nuevo horario.
                                if($conexion_base->query("INSERT INTO horario(hora_inicial, hora_final)
                                VALUES('" . $_POST["hora-entrada"] . "', '" . $_POST["hora-salida"] . "');")) {
                                    $ID_horario = $conexion_base->insert_id;
                                }   
                                else {
                                    $resultado = 1;
                                }
                            }
                            $horarios->close();

                            # Agregar al colaborador a la base de datos.
                            try {
                                if($conexion_base->query("INSERT INTO colaborador(ID, nombres, apellido_paterno, apellido_materno, 
                                ID_carrera, ID_modalidad, ID_horario) VALUES('" . $_POST["ID-colaborador"] . "', '$nombres', '$apellido_paterno', '$apellido_materno', '" 
                                . $_POST["carrera"] . "', '" . $_POST["modalidad"] . "', '$ID_horario');")) {
                                    $resultado = 4;
                                    $conexion_base->query("COMMIT;");
                                }
                                else {
                                    $resultado = 1;
                                    $conexion_base->query("ROLLBACK;");
                                }
                            }
                            catch(Exception $e) {
                                $resultado = 1;
                                $conexion_base->query("ROLLBACK;");
                            }
                        } 
                        else {
                            $resultado = 1;
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
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-gH2yIJqKdNHPEq0n4Mqa/HGKIhSkIHeL5AyhkYV8i59U5AR6csBvApHHNl/vI1Bx" crossorigin="anonymous">

        <!--Título de la página-->
        <title> Resultado de la adición del colaborador </title>

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
        <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"> </script>
        <script type="text/javascript"> 
            let estadoInicioSesion = <?php echo $resultado?>;
            switch(estadoInicioSesion) {
                case 1:
                    window.addEventListener("load", () => {
                        Swal.fire({
                            icon: "error",
                            title: "Adición no exitosa de colaborador",
                            text: "Ocurrió un error al tratar de añadir al colaborador"
                        }).then((resultado) => {
                            location.href="adicion_colaborador.php";
                        });
                    });
                break;

                case 2:
                    window.addEventListener("load", () => {
                        Swal.fire({
                            icon: "error",
                            title: "Identificador de colaborador ya existente",
                            text: "El ID del colaborador nuevo no puede ser uno ya existente"
                        }).then((resultado) => {
                            location.href="adicion_colaborador.php";
                        });
                    });
                break;

                case 3:
                    window.addEventListener("load", () => {
                        Swal.fire({
                            icon: "error",
                            title: "Rango de horario incorrecto",
                            text: "Las horas de entrada y salida indicadas no son válidas"
                        }).then((resultado) => {
                            location.href="adicion_colaborador.php";
                        });
                    });
                break;

                case 4:
                    window.addEventListener("load", () => {
                        Swal.fire({
                            icon: "success",
                            title: "Adición exitosa de colaborador",
                            html: <?php echo "\"<p class='mb-4'> El siguiente colaborador fue exitosamente registrado en el sistema: </p> \\n"
                            . "<p class='my-2'> <b> Colaborador: </b> " . @$nombres . " " . @$apellido_paterno . " " . @$apellido_materno . " </p> \\n"
                            . "<p class='mb-2'> <b> ID: </b> " . @$_POST["ID-colaborador"] . "</p> \\n"
                            . "<p class='mb-2'> <b> Hora de entrada: </b> " . date("h:i:s A", strtotime(@$tiempo_entrada)) . "</p> \\n"
                            . "<p class='mb-0'> <b> Hora de salida: </b>" . date("h:i:s A", strtotime(@$tiempo_salida)) . " </p>\""
                            ?>
                        }).then((resultado) => {
                            location.href="adicion_colaborador.php";
                        });
                    });
                break;

                default:
                    window.addEventListener("load", () => {
                        Swal.fire({
                            icon: "error",
                            title: "Error desconocido",
                            text: "Ocurrió un error al tratar de añadir al colaborador"
                        }).then((resultado) => {
                            location.href="adicion_colaborador.php";
                        });
                    });
                break;
            }
        </script>
    </body>
</html>