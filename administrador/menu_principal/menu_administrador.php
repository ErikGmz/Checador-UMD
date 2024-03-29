<?php
    session_start();

    # Verificar si algún administrador no
    # ha iniciado su correspondiente sesión.
    if(!isset($_SESSION["ID_administrador"])) {
        header("location: ../gestion_sesion/inicio_sesion.php");
        die();
    }
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
        <link rel="stylesheet" href="../../css/estilos_administradores.css">
        <link rel="stylesheet" href="../../css/bootstrap/bootstrap-icons.css">
        <link rel="stylesheet" href="../../css/bootstrap/bootstrap.min.css">

        <!--Título de la página-->
        <title> Menú de administrador </title>

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
        <div class="mx-2 mx-md-0 px-2 px-lg-0">
            <div class="container my-5 px-0 px-lg-5">
                <div class="jumbotron fondo-pantone-azul-claro">
                    <h1 class="fs-2 fw-semibold"> 
                        Bienvenido, administrador no. <?php echo $_SESSION["ID_administrador"]; ?>
                    </h1>

                    <p class="lead">
                        Este es el sistema de administración del checador para colaboradores
                        de la Unidad Médico Didáctica de la Universidad Autónoma de Aguascalientes.
                    </p>
                    <hr class="my-4 border border-1 border-dark">

                    <p class="fw-semibold">
                        Con los colaboradores se pueden realizar las siguientes operaciones:
                    </p>

                    <ul class="ps-4 mb-4">
                        <li class="mb-1"> Consultar los colaboradores actualmente registrados. </li>
                        <li class="mb-1"> Agregar colaboradores nuevos. </li>
                        <li class="mb-1"> Modificar la información de colaboradores existentes. </li>
                        <li class="mb-1"> Eliminar colaboradores del sistema. </li>
                        <li class="mb-1"> Desbloquear y/o bloquear los ID's de colaboradores. </li>
                        <li class="mb-1"> Revisar la actividad de los colaboradores. </li>
                    </ul>

                    <p class="fw-semibold">
                        Con las contingencias se pueden realizar las siguientes operaciones:
                    </p>

                    <ul class="ps-4 mb-4">
                        <li class="mb-1"> Consultar las contingencias existentes. </li>
                        <li class="mb-1"> Agregar contingencias nuevas. </li>
                        <li class="mb-1"> Modificar contingencias existentes. </li>
                        <li> Eliminar contingencias. </li>
                    </ul>

                    <p class="fw-semibold">
                        Con los administradores se pueden realizar las siguientes operaciones:
                    </p>

                    <ul class="ps-4 mb-4">
                        <li class="mb-1"> Consultar los administradores actualmente registrados. </li>
                        <li class="mb-1"> Agregar administradores nuevos. </li>
                        <li class="mb-1"> Modificar la información de administradores existentes. </li>
                        <li class="mb-1"> Eliminar administradores del sistema. </li>
                    </ul>

                    <p class="fw-semibold">
                        Con los chequos se pueden realizar las siguientes operaciones:
                    </p>

                    <ul class="ps-4 mb-0">
                        <li class="mb-1"> Agregar chequeos nuevos. </li>
                        <li class="mb-1"> Modificar la información de los chequeos existentes. </li>
                        <li class="mb-1"> Eliminar chequeos del sistema. </li>
                    </ul>
                </div>
            </div>
        </div>

        <!--Scripts de la página-->
        <script src="../../js/confirmar_operaciones.js"> </script>
        <script src="../../js/sweetalert2/sweetalert2@11.js"> </script>
        <script src="../../js/bootstrap/jquery-3.6.0.min.js"> </script>
        <script src="../../js/bootstrap/bootstrap.bundle.min.js"> </script>
        <script type="text/javascript">   
            document.body.onload = () => {
                document.querySelector("html").classList.remove("invisible");
            }

            document.getElementById("cierre-sesion").addEventListener("click", () => {
                document.getElementById("formulario").requestSubmit();
            });
            document.getElementById("formulario").addEventListener("submit", confirmarCierreSesion);
        </script>
    </body>
</html>