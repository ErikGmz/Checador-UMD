<?php
    session_start();

    # Verificar si algún administrador ya 
    # inició su correspondiente sesión.
    if(isset($_SESSION["ID_administrador"])) {
        header("location: administrador/menu_principal/menu_administrador.php");
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
        <link rel="stylesheet" href="css/clases_globales.css">
        <link rel="stylesheet" href="css/estilos_usuarios.css">
        <link rel="stylesheet" href="css/bootstrap/bootstrap-icons.css">
        <link rel="stylesheet" href="css/bootstrap/bootstrap.min.css">

        <!--Título de la página-->
        <title> Checador Unidad Médico Didáctica </title>

        <!--Ícono de la página-->
        <link rel="apple-touch-icon" sizes="76x76" href="favicon/apple-touch-icon.png">
        <link rel="icon" type="image/png" sizes="32x32" href="favicon/favicon-32x32.png">
        <link rel="icon" type="image/png" sizes="16x16" href="favicon/favicon-16x16.png">
        <link rel="manifest" href="site.webmanifest">
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
                    include("componentes_interfaz/logotipos_responsivos_pagina.php");
                ?>

                <!--Recuadro para los chequeos de los colaboradores-->
                <div class="col-12 col-lg-8 centrado-flex">
                    <div class="rounded-4 p-4 fondo-pantone-azul-claro contenido">
                        <!--Título-->
                        <h4 class="text-center"> Registros de entradas y salidas </h4>
                        <hr class="border border-1 border-dark mb-4"> 

                        <form class="mb-0" method="POST" action="gestion_chequeos/almacenar_chequeo.php">
                            <div class="row px-1">
                                <!--Elección del tipo de registro-->
                                <div class="col-12 mb-4">
                                    <div class="rounded-2 fondo-pantone-verde-claro recuadro mx-auto"> 
                                        <h6 class="text-center text-white fondo-pantone-verde-oscuro py-3 rounded-top mb-0 px-3"> 
                                            Tipo de registro 
                                        </h6>

                                        <div class="py-4">
                                            <div class="form-check d-flex justify-content-center">
                                                <div>
                                                    <input class="form-check-input mb-2" type="radio" name="chequeo" value="entrada" id="opcion-entrada" checked>
                                                    <label class="form-check-label fw-semibold" for="opcion-entrada">
                                                        Tiempo de entrada
                                                    </label>
                                                </div>
                                            </div>
    
                                            <div class="form-check d-flex justify-content-center">
                                                <div>
                                                    <input class="form-check-input" type="radio" name="chequeo" value="salida" id="opcion-salida">
                                                    <label class="form-check-label fw-semibold" for="opcion-salida">
                                                        Tiempo de salida
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!--Datos de registro-->
                                <div class="col-12 mb-4">
                                    <div class="rounded-2 text-center fondo-pantone-azul-intermedio recuadro mx-auto"> 
                                        <h6 class="text-center text-white fondo-pantone-azul-oscuro py-3 rounded-top mb-0 px-3"> 
                                            Datos de registro
                                        </h6>

                                        <div class="p-4">
                                            <label for="ID-colaborador" class="form-label fw-semibold"> ID del colaborador </label>
                                            <input type="text" name="ID" class="form-control text-center mx-auto" id="ID-colaborador" autocomplete="OFF" required>
                                        </div>
                                    </div>
                                </div>  

                                <!--Botón de envío-->
                                <div class="col-12 text-center">
                                    <button type="submit" class="btn btn-primary fw-semibold">
                                        Realizar registro
                                    </button>
                                </div>  
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!--Scripts de la página-->
        <script src="js/bootstrap/jquery-3.6.0.min.js"> </script>
        <script src="js/bootstrap/bootstrap.bundle.min.js"> </script>
        <script src="js/enfocar_recuadro.js"> </script>
        <script type="text/javascript">
            document.body.onload = () => {
                document.querySelector("html").classList.remove("invisible");

                // Seleccionar automáticamente el recuadro
                // del identificador del colaborador al
                // escoger el tipo de registro.
                enfocarRecuadro("ID-colaborador");
            }
            let casillaEntrada = document.getElementById("opcion-entrada");
            let casillaSalida = document.getElementById("opcion-salida");
            let recuadroID = document.getElementById("ID-colaborador");

            casillaEntrada.addEventListener("click", () => {
                enfocarRecuadro("ID-colaborador");
            });

            casillaSalida.addEventListener("click", () => {
                enfocarRecuadro("ID-colaborador");
            });

            recuadroID.addEventListener("keyup", (event) => {
                if(event.keyCode == 38 ||event.keyCode == 40) {
                    if(casillaEntrada.checked) {
                        casillaSalida.checked = true;
                        casillaEntrada.checked = false;
                    }
                    else {
                        casillaSalida.checked = false;
                        casillaEntrada.checked = true;
                    }
                }
            });
        </script>
    </body>
</html>