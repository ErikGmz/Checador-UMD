<?php
    session_start();

    # Verificar si algún administrador ya 
    # inició su correspondiente sesión.
    if(isset($_SESSION["ID_administrador"])) {
        header("location: ../administrador/menu_principal/menu_administrador.php");
        die();
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
        <link rel="stylesheet" href="../css/clases_globales.css">
        <link rel="stylesheet" href="../css/estilos_usuarios.css">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.9.1/font/bootstrap-icons.css">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-gH2yIJqKdNHPEq0n4Mqa/HGKIhSkIHeL5AyhkYV8i59U5AR6csBvApHHNl/vI1Bx" crossorigin="anonymous">

        <!--Título de la página-->
        <title> Solicitud de datos del colaborador </title>

        <!--Ícono de la página-->
        <link rel="apple-touch-icon" sizes="76x76" href="../favicon/apple-touch-icon.png">
        <link rel="icon" type="image/png" sizes="32x32" href="../favicon/favicon-32x32.png">
        <link rel="icon" type="image/png" sizes="16x16" href="../favicon/favicon-16x16.png">
        <link rel="manifest" href="../site.webmanifest">
        <link rel="mask-icon" href="../favicon/safari-pinned-tab.svg" color="#5bbad5">
        <meta name="msapplication-TileColor" content="#da532c">
        <meta name="theme-color" content="#ffffff">
    </head>

    <!--Cuerpo de la página-->
    <body class="fondo-pantone-azul-oscuro">
        <div class="container-xxl min-vh-100 px-4 py-5">
            <div class="row interfaz">
                <!--Logotipos y botones de la página-->
                <?php
                    include("../componentes_interfaz/logotipos_responsivos_pagina.php");
                ?>

                <!--Recuadro para los datos de revisión-->
                <div class="col-12 col-lg-8 centrado-flex">
                    <div class="rounded-4 p-4 fondo-pantone-azul-claro contenido">
                        <!--Título-->
                        <h4 class="text-center"> Revisión de horas </h4>
                        <hr class="border border-1 border-dark mb-4"> 

                        <form class="mb-0" method="GET" action="listado_horas.php">
                            <div class="row px-1">
                                <!--Elección del tipo de revisión-->
                                <div class="col-12 mb-4">
                                    <div class="rounded-2 fondo-pantone-verde-claro recuadro mx-auto"> 
                                        <h6 class="text-center text-white fondo-pantone-verde-oscuro py-3 rounded-top mb-0 px-3"> 
                                            Tipo de revisión 
                                        </h6>

                                        <div class="py-4">
                                            <div class="form-check d-flex justify-content-center">
                                                <div>
                                                    <input class="form-check-input mb-2" type="radio" name="chequeo" value="desglose" id="desglosadas" checked>
                                                    <label class="form-check-label fw-semibold" for="desglosadas">
                                                        Horas desglosadas
                                                    </label>
                                                </div>
                                            </div>
    
                                            <div class="form-check d-flex justify-content-center">
                                                <div>
                                                    <input class="form-check-input" type="radio" name="chequeo" value="resumen" id="resumidas">
                                                    <label class="form-check-label fw-semibold" for="resumidas">
                                                        Horas resumidas
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!--Datos de revisión-->
                                <div class="col-12 mb-4">
                                    <div class="rounded-2 text-center fondo-pantone-azul-intermedio recuadro mx-auto"> 
                                        <h6 class="text-center text-white fondo-pantone-azul-oscuro py-3 rounded-top mb-0 px-3"> 
                                            Datos de revisión
                                        </h6>

                                        <div class="row p-4"> 
                                            <div class="col-12 col-lg-6 mb-3">
                                                <label for="ID-colaborador" class="form-label fw-semibold"> ID del colaborador </label>
                                                <input type="text" name="ID-colaborador" class="form-control text-center mx-auto" id="ID-colaborador" autocomplete="OFF" required>
                                            </div>

                                            <div class="col-12 col-lg-6 mb-3">
                                                <label for="horas-becario" class="form-label fw-semibold"> Horas de becario </label>
                                                <input type="number" name="horas-becario" min="0" value="0" class="form-control text-center mx-auto" id="horas-becario" autocomplete="OFF" required>
                                            </div>

                                            <div class="col-12 col-lg-6 mb-3 mb-lg-0">
                                                <label for="fecha-inicial" class="form-label fw-semibold"> Fecha inicial </label>
                                                <input type="date" name="fecha-inicial" value="2021-01-01" min="2021-01-01" max="2030-12-30" class="form-control text-center mx-auto" id="fecha-inicial" autocomplete="OFF" required>
                                            </div>

                                            <div class="col-12 col-lg-6">
                                                <label for="fecha-final" class="form-label fw-semibold"> Fecha final </label>
                                                <input type="date" name="fecha-final"  min="2021-01-02" max="2030-12-31" class="form-control text-center mx-auto" id="fecha-final" autocomplete="OFF" required>
                                            </div>
                                        </div>
                                    </div>
                                </div>  

                                <!--Botón de envío-->
                                <div class="col-12 text-center">
                                    <button type="submit" class="btn btn-primary fw-semibold">
                                        Realizar revisión
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
            // Mostrar el contenido una vez que la
            // página se cargue por completo.
            window.onload = () => {
                document.querySelector("html").classList.remove("d-none");
                setTimeout(() => {
                    document.querySelector("html").classList.remove("invisible");
                }, 20);
            }

            document.getElementById("fecha-final").value = new Date().toISOString().substring(0, 10);
            // Seleccionar automáticamente el recuadro
            // del identificador del colaborador al
            // escoger el tipo de revisión.
            enfocarRecuadro("ID-colaborador");
            
            document.getElementById("resumidas").addEventListener("click", () => {
                enfocarRecuadro("ID-colaborador");
            });

            document.getElementById("desglosadas").addEventListener("click", () => {
                enfocarRecuadro("ID-colaborador");
            });
        </script>
    </body>
</html>