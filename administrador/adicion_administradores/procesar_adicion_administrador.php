<?php
    session_start();

    # Verificar si algún administrador ya
    # inició su correspondiente sesión.
    if(!isset($_SESSION["ID_administrador"])) {
        header("location: ../menu_principal/menu_administrador.php");
        die();
    }

    # Verificar que se haya enviado un
    # formulario de adición de administrador.
    if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST["ID-administrador"], $_POST["clave"], $_POST["confirmacion-clave"])) {
        # Iniciar y verificar la conexión
        # con la base de datos.
        $conexion_base = new mysqli("localhost", "root", "", "checadorumd");
        if($conexion_base->connect_error) {
            die("Hubo un error al conectar con la base de datos. " . $conexion_base->connect_error);
        }

        # Verificar si los datos especificados no corresponden
        # a un administrador ya existente en el sistema.
        try {
            if($resultados = $conexion_base->query("SELECT * FROM coordinador WHERE 
            ID = '" . $_POST["ID-administrador"] . "';")) {
                if($resultados->num_rows > 0) {
                    $resultado = 2;
                }
                else {
                    if($_POST["clave"] != $_POST["confirmacion-clave"]) {
                        $resultado = 3;
                    }
                    else {
                        # Agregar el administrador a la base de datos.
                        try {
                            if($conexion_base->query("INSERT INTO coordinador(ID, clave) "
                            . "VALUES('" . $_POST["ID-administrador"] . "', MD5('" . $_POST["clave"] . "'));"))  {
                                $resultado = 4;
                            }
                            else {
                                $resultado = 1;
                            }
                        }
                        catch(Exception $e) {
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
        <link rel="stylesheet" href="../../css/bootstrap/bootstrap.min.css">

        <!--Título de la página-->
        <title> Resultado de la adición del administrador </title>

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
                            title: "Adición no exitosa de administrador",
                            text: "Ocurrió un error al tratar de añadir al administrador"
                        }).then((resultado) => {
                            location.href="adicion_administrador.php";
                        });
                    });
                break;

                case 2:
                    window.addEventListener("load", () => {
                        Swal.fire({
                            icon: "error",
                            title: "Administrador ya existente",
                            html: <?php echo "\"<p class='mb-4'> El siguiente administrador ya existe en el sistema: </p> \\n"
                            . "<p class='mt-2 mb-0'> <b> Administrador: </b> " . @$_POST["ID-administrador"] . " </p>\""
                            ?>
                        }).then((resultado) => {
                            location.href="adicion_administrador.php";
                        });
                    });
                break;

                case 3:
                    window.addEventListener("load", () => {
                        Swal.fire({
                            icon: "error",
                            title: "Fallo de confirmación de contraseña",
                            text: "La contraseña y confirmación introducidas no coinciden"
                        }).then((resultado) => {
                            location.href="adicion_administrador.php";
                        });
                    });
                break;

                case 4:
                    window.addEventListener("load", () => {
                        Swal.fire({
                            icon: "success",
                            title: "Adición exitosa de administrador",
                            html: <?php echo "\"<p class='mb-4'> El siguiente administrador fue exitosamente registrado en el sistema: </p> \\n"
                            . "<p class='mt-2 mb-0'> <b> Administrador: </b> " . @$_POST["ID-administrador"] . " </p>\""
                            ?>
                        }).then((resultado) => {
                            location.href="adicion_administrador.php";
                        });
                    });
                break;

                default:
                    window.addEventListener("load", () => {
                        Swal.fire({
                            icon: "error",
                            title: "Error desconocido",
                            text: "Ocurrió un error al tratar de añadir al administrador"
                        }).then((resultado) => {
                            location.href="adicion_administrador.php";
                        });
                    });
                break;
            }
        </script>
    </body>
</html>