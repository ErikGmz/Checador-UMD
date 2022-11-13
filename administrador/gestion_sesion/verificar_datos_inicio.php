<?php
    session_start();

    # Verificar si algún administrador ya
    # inició su correspondiente sesión.
    if(isset($_SESSION["ID_administrador"])) {
        header("location: ../menu_principal/menu_administrador.php");
        die();
    }

    # Verificar que se haya enviado un
    # formulario de inicio de sesión.
    if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST["ID-administrador"], $_POST["clave-administrador"])) {
        # Iniciar y verificar la conexión
        # con la base de datos.
        $conexion_base = new mysqli("localhost", "root", "", "checadorumd");
        if($conexion_base->connect_error) {
            die("Hubo un error al conectar con la base de datos. " . $conexion_base->connect_error);
        }

        # Verificar si los datos de inicio de sesión son correctos.
        try {
            if($resultados = $conexion_base->query("SELECT ID FROM coordinador WHERE ID = '" . $_POST["ID-administrador"]
            . "' AND clave = '" . md5($_POST["clave-administrador"]) . "';")) {
                if($resultados->num_rows > 0) {
                    # Cargar los datos de inicio de sesión y
                    # redigir al menú principal de administradores.
                    $_SESSION["ID_administrador"] = $_POST["ID-administrador"];
                    $resultado = 2;
                }
                else {
                    # Los datos de inicio de sesión no fueron
                    # válidos, así que se retornará a la página
                    # de registros de chequeos.
                    $resultado = 1;
                }
                $resultados->close();
            }
            else {
                # Los datos de inicio de sesión no fueron
                # válidos, así que se retornará a la página
                # de registros de chequeos.
                $resultado = 1;
            }   
        }
        catch(Exception $e) { 
            echo $e->getMessage();
            $resultado = 3;
        }
        finally {
            # Cerrar la conexión con la base de datos.
            $conexion_base->close();
        }
    }
    else {
        header("location: ../../index.php");
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
        <title> Resultado del inicio de sesión </title>

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
                            title: "Inicio no exitoso de sesión",
                            text: "El ID del administrador y/o contraseña introducidos son incorrectos"
                        }).then((resultado) => {
                            location.href="inicio_sesion.php";
                        });
                    });
                break;

                case 2:
                    window.addEventListener("load", () => {
                        Swal.fire({
                            icon: 'success',
                            title: 'Inicio de sesión exitoso',
                            text: "Bienvenido al sistema de administración, usuario no. " + <?php echo "'" . @$_SESSION["ID_administrador"] . "'" ?>
                        }).then((resultado) => {
                            location.href="../menu_principal/menu_administrador.php";
                        });
                    });
                break;

                default:
                    window.addEventListener("load", () => {
                        Swal.fire({
                            icon: "error",
                            title: "Error desconocido",
                            text: "Ocurrió un error al tratar de iniciar la sesión"
                        }).then((resultado) => {
                            location.href="inicio_sesion.php";
                        });
                    });
                break;
            }
        </script>
    </body>
</html>