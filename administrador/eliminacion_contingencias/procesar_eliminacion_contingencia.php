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
    # formulario de eliminación de contingencia.
    if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST["ID-colaborador"], $_POST["fecha-registro"])) {
        # Iniciar y verificar la conexión
        # con la base de datos.
        $conexion_base = new mysqli("localhost", "root", "", "checadorumd");
        if($conexion_base->connect_error) {
            die("Hubo un error al conectar con la base de datos. " . $conexion_base->connect_error);
        }

        # Verificar si los datos especificados corresponden
        # a una contingencia existente en el sistema.
        try {
            if($resultados = $conexion_base->query("SELECT * FROM contingencia WHERE 
            ID_colaborador = '" . $_POST["ID-colaborador"] . "' AND fecha = '" . $_POST["fecha-registro"] . "';")) {
                if($resultados->num_rows <= 0) {
                    $resultado = 2;
                }
                else {
                    if(strtotime($_POST["fecha-registro"]) >= strtotime("2021-01-01")) {
                        # Borrar la contingencia en la base de datos.
                        try {
                            if($conexion_base->query("DELETE FROM contingencia WHERE fecha = '" . $_POST["fecha-registro"]
                            . "' AND ID_colaborador = '" . $_POST["ID-colaborador"] . "';"))  {
                                $resultado = 4;
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
                        $resultado = 3;
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
                            title: "Eliminación no exitosa de contingencia",
                            text: "Ocurrió un error al tratar de eliminar la contingencia"
                        }).then((resultado) => {
                            location.href="eliminacion_contingencia.php";
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
                            location.href="eliminacion_contingencia.php";
                        });
                    });
                break;

                case 3:
                    window.addEventListener("load", () => {
                        Swal.fire({
                            icon: "error",
                            title: "Fecha de registro no válida",
                            text: "La fecha de registro no corresponde al rango de fechas permitido (01-01-2021 o mayor)"
                        }).then((resultado) => {
                            location.href="eliminacion_contingencia.php";
                        });
                    });
                break;

                case 4:
                    window.addEventListener("load", () => {
                        Swal.fire({
                            icon: "success",
                            title: "Eliminación exitosa de contingencia",
                            html: <?php echo "\"<p class='mb-4'> La siguiente contingencia fue exitosamente eliminada del sistema: </p> \\n"
                            . "<p class='my-2'> <b> Colaborador: </b> " . @$_POST["ID-colaborador"] . " </p> \\n"
                            . "<p class='mb-0'> <b> Fecha de registro: </b> " . date("d-m-Y", strtotime(@$_POST["fecha-registro"])). "</p>\""
                            ?>
                        }).then((resultado) => {
                            location.href="eliminacion_contingencia.php";
                        });
                    });
                break;

                default:
                    window.addEventListener("load", () => {
                        Swal.fire({
                            icon: "error",
                            title: "Error desconocido",
                            text: "Ocurrió un error al tratar de eliminar la contingencia"
                        }).then((resultado) => {
                            location.href="eliminacion_contingencia.php";
                        });
                    });
                break;
            }
        </script>
    </body>
</html>