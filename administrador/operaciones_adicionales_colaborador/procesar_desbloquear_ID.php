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
    # formulario de desbloqueo de ID.
    if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST["ID-colaborador"])) {
        # Iniciar y verificar la conexión
        # con la base de datos.
        $conexion_base = new mysqli("localhost", "root", "", "checadorumd");
        if($conexion_base->connect_error) {
            die("Hubo un error al conectar con la base de datos. " . $conexion_base->connect_error);
        }

        # Verificar si el ID especificado sí
        # está registrado en en el sistema y
        # se encuentra bajo un bloqueo.
        try {
            if($resultados = $conexion_base->query("SELECT * FROM colaborador WHERE ID = '" 
            . $_POST["ID-colaborador"] . "' AND numero_retardos > 2;")) {
                if($resultados->num_rows <= 0) {
                    $resultado = 2;
                }
                else {
                    # Desbloquear al colaborador registrado
                    # en la base de datos.
                    try {
                        if($conexion_base->multi_query("UPDATE colaborador SET numero_retardos = '0', numero_desbloqueos = numero_desbloqueos + 1
                        WHERE ID = '" . $_POST["ID-colaborador"] . "'; 
                        UPDATE chequeo SET bloqueo_registro = 0 WHERE ID_colaborador = '"
                        . $_POST["ID-colaborador"] . "' AND fecha_chequeo = CURDATE();")) {
                            $auxiliar = $resultados->fetch_row();

                            $nombres = $auxiliar[1];
                            $apellido_paterno = $auxiliar[2];
                            $apellido_materno = $auxiliar[3];
                            $resultado = 3;
                        }
                        else {
                            $resultado = 1;
                        }
                    }
                    catch(mysqli_sql_exception $e) {
                        echo $e->getMessage();
                        $resultado = 1;
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
        <title> Resultado del desbloqueo del colaborador </title>

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
                            title: "Desbloqueo no exitoso de colaborador",
                            text: "Ocurrió un error al tratar de desbloquear al colaborador"
                        }).then((resultado) => {
                            location.href="desbloquear_ID.php";
                        });
                    });
                break;

                case 2:
                    window.addEventListener("load", () => {
                        Swal.fire({
                            icon: "error",
                            title: "Identificador de colaborador inexistente o no bloqueado",
                            text: "El ID especificado no puede desbloquearse en este momento"
                        }).then((resultado) => {
                            location.href="desbloquear_ID.php";
                        });
                    });
                break;

                case 3:
                    window.addEventListener("load", () => {
                        Swal.fire({
                            icon: "success",
                            title: "Desbloqueo exitoso de colaborador",
                            html: <?php echo "\"<p class='mb-4'> El siguiente colaborador fue exitosamente desbloqueado: </p> \\n"
                            . "<p class='my-2'> <b> Colaborador: </b> " . @$nombres . " " . @$apellido_paterno . " " . @$apellido_materno . " </p> \\n"
                            . "<p class='mb-2'> <b> ID: </b> " . @$_POST["ID-colaborador"] . "</p>\""
                            ?>
                        }).then((resultado) => {
                            location.href="desbloquear_ID.php";
                        });
                    });
                break;

                default:
                    window.addEventListener("load", () => {
                        Swal.fire({
                            icon: "error",
                            title: "Error desconocido",
                            text: "Ocurrió un error al tratar de desbloquear el colaborador"
                        }).then((resultado) => {
                            location.href="desbloquear_ID.php";
                        });
                    });
                break;
            }
        </script>
    </body>
</html>