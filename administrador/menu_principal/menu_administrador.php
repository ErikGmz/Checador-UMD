<?php
    session_start();

    # Verificar si algún administrador no
    # ha iniciado su correspondiente sesión.
    if(!isset($_SESSION["ID_administrador"])) {
        header("location: inicio_sesion.php");
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
        <link rel="stylesheet" href="../../css/clases_globales.css">
        <link rel="stylesheet" href="../../css/estilos_administradores.css">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.9.1/font/bootstrap-icons.css">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-gH2yIJqKdNHPEq0n4Mqa/HGKIhSkIHeL5AyhkYV8i59U5AR6csBvApHHNl/vI1Bx" crossorigin="anonymous">

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
                        <li class="mb-1"> Desbloquear los ID's de colaboradores. </li>
                        <li class="mb-1"> Revisar la actividad de los colaboradores. </li>
                    </ul>

                    <p class="fw-semibold">
                        Con las contingencias se pueden realizar las siguientes operaciones:
                    </p>

                    <ul class="ps-4 mb-0">
                        <li class="mb-1"> Consultar las contingencias existentes. </li>
                        <li class="mb-1"> Agregar contingencias nuevas. </li>
                        <li class="mb-1"> Modificar contingencias existentes. </li>
                        <li> Eliminar contingencias. </li>
                    </ul>
                </div>
            </div>
        </div>

        <!--Scripts de la página-->
        <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"> </script>
        <script src="../../js/confirmar_operaciones.js"> </script>
        <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.5/dist/umd/popper.min.js" integrity="sha384-Xe+8cL9oJa6tN/veChSP7q+mnSPaj5Bcu9mPX5F5xIGE0DVittaqT5lorf0EI7Vk" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.min.js" integrity="sha384-ODmDIVzN+pFdexxHEHFBQH3/9/vQ9uori45z4JjnFsRydbmQbmL5t1tQ0culUzyK" crossorigin="anonymous"></script>
        <script type="text/javascript">
            document.getElementById("cierre-sesion").addEventListener("click", () => {
                document.getElementById("formulario").requestSubmit();
            });
            document.getElementById("formulario").addEventListener("submit", confirmarCierreSesion);
        </script>
    </body>
</html>