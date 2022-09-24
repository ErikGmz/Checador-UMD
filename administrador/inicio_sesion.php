<?php
    session_start();

    # Verificar si algún administrador ya
    # inició su correspondiente sesión.
    if(isset($_SESSION["ID_administrador"])) {
        header("location: menu_administrador.php");
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
        <link rel="stylesheet" href="../css/clases_globales.css">
        <link rel="stylesheet" href="../css/estilos_usuarios.css">
        <link rel="stylesheet" href="../css/estilos_inicio_sesion.css">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.9.1/font/bootstrap-icons.css">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-gH2yIJqKdNHPEq0n4Mqa/HGKIhSkIHeL5AyhkYV8i59U5AR6csBvApHHNl/vI1Bx" crossorigin="anonymous">

        <!--Título de la página-->
        <title> Inicio de sesión como administrador </title>

        <!--Ícono de la página-->
        <link rel="apple-touch-icon" sizes="76x76" href="../favicon/apple-touch-icon.png">
        <link rel="icon" type="image/png" sizes="32x32" href="../favicon/favicon-32x32.png">
        <link rel="icon" type="image/png" sizes="16x16" href="../favicon/favicon-16x16.png">
        <link rel="manifest" href="../site.webmanifest">
        <link rel="mask-icon" href="favicon/safari-pinned-tab.svg" color="#5bbad5">
        <meta name="msapplication-TileColor" content="#da532c">
        <meta name="theme-color" content="#ffffff">
    </head>

    <!--Cuerpo de la página-->
    <body class="fondo-pantone-azul-oscuro">
        <div class="container-xxl min-vh-100 p-4 py-lg-0">
            <div class="row interfaz">
                <!--Logotipos y botones de la página-->
                <?php
                    include("../componentes_interfaz/logotipos_responsivos_pagina.php");
                ?>

                <!--Recuadro para los chequeos de los colaboradores-->
                <div class="col-12 col-lg-8 centrado-flex">
                    <div class="rounded-4 p-4 fondo-pantone-azul-claro contenido">
                        <!--Título-->
                        <h4 class="text-center"> Inicio de sesión como administrador </h4>
                        <hr class="border border-1 border-dark mb-4"> 

                        <form class="mb-0" method="POST" action="verificar_datos_inicio.php">
                            <div class="row px-1">
                                <!--Datos de inicio de sesión-->
                                <div class="col-12 mb-4">
                                    <div class="rounded-2 text-center fondo-pantone-azul-intermedio recuadro mx-auto"> 
                                        <h6 class="text-center text-white fondo-pantone-azul-oscuro py-3 rounded-top mb-0 px-3"> 
                                            Datos de inicio de sesión
                                        </h6>

                                        <div class="p-4">
                                            <div class="mb-4">
                                                <label for="ID-administrador" class="form-label fw-semibold"> ID del administrador </label>
                                                <input type="text" name="ID-administrador" class="form-control text-center mx-auto" id="ID-administrador" autocomplete="OFF" required>
                                            </div>

                                            <div>
                                                <label for="clave-administrador" class="form-label fw-semibold"> Contraseña </label>
                                                <input type="password" name="clave-administrador" class="form-control text-center mx-auto" id="clave-administrador" autocomplete="OFF" required>
                                            </div>
                                        </div>
                                    </div>
                                </div>  

                                <!--Botón de envío-->
                                <div class="col-12 text-center">
                                    <button type="submit" class="btn btn-primary fw-semibold">
                                        Iniciar sesión
                                    </button>
                                </div>  
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!--Scripts de la página-->
        <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.5/dist/umd/popper.min.js" integrity="sha384-Xe+8cL9oJa6tN/veChSP7q+mnSPaj5Bcu9mPX5F5xIGE0DVittaqT5lorf0EI7Vk" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.min.js" integrity="sha384-ODmDIVzN+pFdexxHEHFBQH3/9/vQ9uori45z4JjnFsRydbmQbmL5t1tQ0culUzyK" crossorigin="anonymous"></script>
        <script src="../js/enfocar_recuadro.js"> </script>
        <script type="text/javascript">
            // Seleccionar automáticamente el recuadro
            // del identificador del administrador.
            enfocarRecuadro("ID-administrador");
        </script>
    </body>
</html>