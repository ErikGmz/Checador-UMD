<?php
    session_start();

    # Verificar si algún administrador no
    # ha iniciado su correspondiente sesión.
    if(!isset($_SESSION["ID_administrador"])) {
        header("location: ../menu_principal/menu_administrador.php");
        die();
    }

    # Iniciar y verificar la conexión
    # con la base de datos.
    $conexion_base = new mysqli("localhost", "root", "", "checadorumd");
    if($conexion_base->connect_error) {
        die("Hubo un error al conectar con la base de datos. " . $conexion_base->connect_error);
    }

    # Obtener todos los administradores registrados.
    $administradores = $conexion_base->query("SELECT * FROM coordinador WHERE ID NOT IN 
    ('" . $_SESSION["ID_administrador"] . "', '141414');");
?>

<!--Código HTML del archivo-->
<html lang="es" class="invisible">
    <!--Cabecera de la página-->
    <head>
        <!--Metadatos de la página-->
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">

        <!--Estilos de la página-->
        <link rel="stylesheet" href="../../css/clases_globales.css">
        <link rel="stylesheet" href="../../css/desplazamiento_instantaneo.css">
        <link rel="stylesheet" href="../../css/estilos_administradores.css">
        <link rel="stylesheet" href="../../css/bootstrap/bootstrap-icons.css">
        <link rel="stylesheet" href="../../css/bootstrap/bootstrap.min.css">
        <link rel="stylesheet" href="../../css/dselect.min.css">
        
        <!--Título de la página-->
        <title> Eliminación de administrador </title>

        <!--Ícono de la página-->
        <link rel="apple-touch-icon" sizes="76x76" href="../../favicon/apple-touch-icon.png">
        <link rel="icon" type="image/png" sizes="32x32" href="../../favicon/favicon-32x32.png">
        <link rel="icon" type="image/png" sizes="16x16" href="../../favicon/favicon-16x16.png">
        <link rel="manifest" href="../../site.webmanifest">
        <link rel="mask-icon" href="favicon/safari-pinned-tab.svg" color="#5bbad5">
        <meta name="msapplication-TileColor" content="#da532c">
        <meta name="theme-color" content="#ffffff">
    </head>

    <!--Cuerpo de la página-->
    <body class="fondo-pantone-azul-oscuro">
        <!--Barra de navegación-->
        <?php
            include("../componentes_paginas/barra_navegacion.php");
        ?>

        <!--Recuadro principal-->
        <div class="mx-2 mx-md-0">
            <div class="container-xl my-5">
                <div class="jumbotron fondo-pantone-azul-claro">
                    <h1 class="fs-2 fw-semibold text-center"> 
                        Eliminación de administrador del sistema
                    </h1>
                    <hr class="my-4 border border-1 border-dark">

                    <!--Formulario de selección del ID de un administrador registrado-->
                    <?php
                    if(isset($administradores) && $administradores->num_rows > 0) {
                    ?>
                    <form method="POST" action="procesar_eliminacion_administrador.php" class="mb-0 px-0 px-md-5"
                    id="eliminacion-administrador">
                        <h5 class="text-center mb-3"> Selección del administrador a eliminar </h5>
                        <div class="mb-4">                            
                            <select class="form-select mb-0" name="ID-administrador" id="administradores" required>
                                <?php
                                    if(isset($administradores) && $administradores->num_rows > 0) {
                                        $numero_administrador = 0;
                                        while($administrador = $administradores->fetch_row()) {
                                            if(@$_GET["ID-administrador"] == $administrador[0]) {
                                                echo "<option selected value='" . $administrador[0] . "'> Administrador no. " 
                                                . ++$numero_administrador . " - " . $administrador[0] . " </option> ";
                                            }
                                            else {
                                                echo "<option value='" . $administrador[0] . "'> Administrador no. " 
                                                . ++$numero_administrador . " - " . $administrador[0] . " </option> ";
                                            }
                                        }
                                    }
                                ?>
                            </select>
                            <div class="form-text text-center">
                                No se puede eliminar al administrador actual o predeterminado.
                            </div>
                        </div>

                        <div class="text-center">
                            <button class="btn btn-primary">
                                Eliminar administrador
                            </button>
                        </div>
                    </form>
                    <?php
                    }
                    else {
                    ?>
                        <h4 class="text-center mt-4 mb-0">
                            <span class="badge bg-danger py-3">
                                No hay administradores a eliminar
                            </span>
                        </h4>
                    <?php
                    }
                    ?>
                </div>
            </div>

            <?php
                # Cerrar la conexión con la base de datos.
                $conexion_base->close();
            ?>
        </div>

        <!--Scripts de la página-->
        <script src="../../js/confirmar_operaciones.js"> </script>
        <script src="../../js/sweetalert2/sweetalert2@11.js"> </script>
        <script src="../../js/bootstrap/jquery-3.6.0.min.js"> </script>
        <script src="../../js/bootstrap/bootstrap.bundle.min.js"> </script>
        <script src="../../js/dselect.min.js"> </script>
        <script type="text/javascript">
            document.body.onload = () => {
                document.querySelector("html").classList.remove("invisible");
            }
            
            document.getElementById("cierre-sesion").addEventListener("click", () => {
                document.getElementById("formulario").requestSubmit();
            });
            document.getElementById("formulario").addEventListener("submit", confirmarCierreSesion);

            <?php
                if(isset($administradores) && $administradores->num_rows > 0) {
                ?>
                    dselect(document.getElementById("administradores"), { search: true, maxHeight: "200px" });
                    document.getElementById("eliminacion-administrador").addEventListener("submit", confirmarEliminacionAdministrador);
                <?php
                }
            ?>
        </script>
    </body>
</html>