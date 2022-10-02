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
?>

<!--Código HTML del archivo-->
<html lang="es" class="d-none invisible">
    <!--Cabecera de la página-->
    <head>
        <!--Metadatos de la página-->
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">

        <!--Estilos de la página-->
        <link rel="stylesheet" href="../../css/desplazamiento_instantaneo.css">
        <link rel="stylesheet" href="../../css/clases_globales.css">
        <link rel="stylesheet" href="../../css/estilos_administradores.css">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.9.1/font/bootstrap-icons.css">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-gH2yIJqKdNHPEq0n4Mqa/HGKIhSkIHeL5AyhkYV8i59U5AR6csBvApHHNl/vI1Bx" crossorigin="anonymous">
        <link rel="stylesheet" href="../../css/dselect.min.css">
        
        <!--Título de la página-->
        <title> Adición de administrador </title>

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
                        Adición de administrador al sistema
                    </h1>
                    <hr class="my-4 border border-1 border-dark">

                    <form method="POST" action="procesar_adicion_administrador.php" 
                    class="mb-0" id="registro-administrador">
                        <div class="row mb-2">
                            <!--Datos del administrador-->
                            <div class="col-12 text-center mb-4">
                                <h4 class="mb-0"> Datos del administrador </h4>
                            </div>

                            <div class="col-12 mb-4 texto-colaborador">
                                <label for="ID-administrador" class="form-label fw-semibold"> ID del administrador (*) </label>
                                <input type="text" class="form-control recuadro-ID" id="ID-administrador" 
                                onchange="verificarAdministrador(document.getElementById('ID-administrador').value, 'ID-administrador', 1)" 
                                autocomplete="OFF" autofocus="ON" required name="ID-administrador" pattern="[0-9]{1,10}">
                                <div class="form-text"> Campo obligatorio. Solo se admite un número de máximo 10 dígitos. </div>
                            </div>

                            <div class="col-12 col-md-6 mb-4 mb-md-0">
                                <label for="clave" class="form-label fw-semibold"> Contraseña (*) </label>
                                <input type="password" class="form-control" id="clave" 
                                autocomplete="OFF" required name="clave" maxlength="30"
                                onchange="verificarClavesIguales('clave', 'confirmacion-clave')">
                                <div class="form-text"> Campo obligatorio. Se puede introducir un máximo de 30 caracteres. </div>
                            </div>

                            <div class="col-12 col-md-6">
                                <label for="confirmacion-clave" class="form-label fw-semibold"> Confirmación de contraseña (*) </label>
                                <input type="password" class="form-control" id="confirmacion-clave" 
                                autocomplete="OFF" required name="confirmacion-clave" maxlength="30"
                                onchange="verificarClavesIguales('clave', 'confirmacion-clave')">
                                <div class="form-text"> Campo obligatorio. Se puede introducir un máximo de 30 caracteres. </div>
                            </div>

                            <div class="col-12 my-4">
                                <hr class="mb-0 border border-1 border-dark">
                            </div>
                        </div>

                        <!--Botón de registro de administrador-->
                        <div class="text-center">
                            <button class="btn btn-primary">
                                Registrar administrador
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <?php
                # Cerrar la conexión con la base de datos.
                $conexion_base->close();
            ?>
        </div>

        <!--Scripts de la página-->
        <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"> </script>
        <script src="../../js/confirmar_operaciones.js"> </script>
        <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.5/dist/umd/popper.min.js" integrity="sha384-Xe+8cL9oJa6tN/veChSP7q+mnSPaj5Bcu9mPX5F5xIGE0DVittaqT5lorf0EI7Vk" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.min.js" integrity="sha384-ODmDIVzN+pFdexxHEHFBQH3/9/vQ9uori45z4JjnFsRydbmQbmL5t1tQ0culUzyK" crossorigin="anonymous"></script>
        <script src="../../js/enfocar_recuadro.js"> </script>
        <script src="../../js/peticiones_ajax/verificar_administrador.js"> </script>
        <script src="../../js/verificar_claves.js"> </script>
        <script type="text/javascript">
            // Mostrar el contenido una vez que la
            // página se cargue por completo.
            window.onload = () => {
                document.querySelector("html").classList.remove("d-none");
                setTimeout(() => {
                    document.querySelector("html").classList.remove("invisible");

                    // Seleccionar automáticamente el recuadro
                    // del identificador del administrador.
                    enfocarRecuadro("ID-administrador");
                    document.getElementById('ID-administrador').setCustomValidity("Completa este campo.");
                }, 20);
            }

            document.getElementById("cierre-sesion").addEventListener("click", () => {
                document.getElementById("formulario").requestSubmit();
            });
            document.getElementById("formulario").addEventListener("submit", confirmarCierreSesion);
            document.getElementById("registro-administrador").addEventListener("submit", confirmarRegistroAdministrador);
        </script>
    </body>
</html>