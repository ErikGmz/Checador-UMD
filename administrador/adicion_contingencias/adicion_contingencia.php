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

    # Obtener todos los colaboradores registrados.
    $colaboradores = $conexion_base->query("SELECT ID, CONCAT_WS(' ', nombres, apellido_paterno, apellido_materno) 
    AS nombre_completo FROM `colaborador`;");
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
        <link rel="stylesheet" href="../../css/desplazamiento_instantaneo.css">
        <link rel="stylesheet" href="../../css/clases_globales.css">
        <link rel="stylesheet" href="../../css/estilos_administradores.css">
        <link rel="stylesheet" href="../../css/bootstrap/bootstrap-icons.css">
        <link rel="stylesheet" href="../../css/bootstrap/bootstrap.min.css">
        <link rel="stylesheet" href="../../css/dselect.min.css">
        
        <!--Título de la página-->
        <title> Adición de contingencia </title>

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
                        Adición de contingencia de colaborador
                    </h1>
                    <hr class="my-4 border border-1 border-dark">

                    <?php
                    if(isset($colaboradores) && $colaboradores->num_rows > 0) {
                    ?>
                    <form method="POST" action="procesar_adicion_contingencia.php" 
                    class="mb-0" id="registro-contingencia">
                        <div class="row mb-2">
                            <!--Datos de la contingencia-->
                            <div class="col-12 text-center mb-4">
                                <h4 class="mb-0"> Datos de la contingencia </h4>
                            </div>

                            <!--Selección del colaborador-->
                            <div class="col-12 col-md-6 mb-4">
                                <label for="colaboradores" class="form-label fw-semibold"> Colaborador (*) </label>
                                <select class="form-select" name="ID-colaborador" id="colaboradores" 
                                onchange="verificarContingencia(document.getElementById('colaboradores').value, 
                                document.getElementById('fecha-registro').value, 'fecha-registro', 1)" required>
                                    <?php
                                        if(isset($colaboradores) && $colaboradores->num_rows > 0) {
                                            while($colaborador = $colaboradores->fetch_row()) {
                                                if(@$_GET["ID-colaborador"] == $colaborador[0]) {
                                                    echo "<option selected value='" . $colaborador[0] . "'> " 
                                                    . $colaborador[0] . " - " . $colaborador[1] . " </option> ";
                                                }
                                                else {
                                                    echo "<option value='" . $colaborador[0] . "'> " 
                                                    . $colaborador[0] . " - " . $colaborador[1] . " </option> ";
                                                }
                                            }
                                        }
                                    ?>
                                </select>
                                <div class="form-text"> 
                                    Campo obligatorio. Si no se selecciona ninguna opción, entonces 
                                    el sistema escogerá por defecto la primera carrera de la lista.
                                </div>
                            </div>

                            <!--Selección de la fecha de contingencia-->
                            <div class="col-12 col-md-6 mb-4">
                                <label for="fecha-registro" class="form-label fw-semibold"> Fecha de contingencia (*) </label>
                                <input type="date" name="fecha-registro" value="2021-01-01" min="2021-01-01" max="2030-12-30" 
                                class="form-control" id="fecha-registro" autocomplete="OFF" required
                                onchange="verificarContingencia(document.getElementById('colaboradores').value, 
                                document.getElementById('fecha-registro').value, 'fecha-registro', 1)">
                                <div class="form-text"> 
                                    Campo obligatorio. El rango de fechas debe 
                                    encontrarse entre 01-01-2021 y 30-12-2030. Cada colaborador
                                    puede tener máximo una contingencia por día.
                                </div>
                            </div>

                            <!--Solicitud de hora inicial-->
                            <div class="col-12 col-md-6 mb-4 mb-md-0">
                                <label for="hora-inicial" class="form-label fw-semibold"> Hora de entrada (*) </label>
                                <input type="text" class="form-control" id="hora-inicial" 
                                autocomplete="OFF" required name="hora-inicial" placeholder="08:00" value="08:00"
                                pattern="^((0[8-9]|1[0-9]|2[0]):[0-5][0-9])|21:00$"
                                oninput="verificarRangosHoras('hora-inicial', 'hora-final')">
                                <div class="form-text"> 
                                    Formato de 08:00 a 21:00 horas.
                                </div>
                            </div>

                            <!--Solicitud de hora final-->
                            <div class="col-12 col-md-6 mb-4">
                                <label for="hora-final" class="form-label fw-semibold"> Hora de salida (*) </label>
                                <input type="text" class="form-control" id="hora-final" 
                                autocomplete="OFF" required name="hora-final" placeholder="12:00" value="12:00"
                                pattern="^((0[8-9]|1[0-9]|2[0]):[0-5][0-9])|21:00$"
                                oninput="verificarRangosHoras('hora-inicial', 'hora-final')">
                                <div class="form-text"> 
                                    Formato de 08:00 a 21:00 horas.
                                </div>
                            </div>

                            <!--Solicitud de observaciones-->
                            <div class="col-12">
                                <label for="observaciones" class="form-label fw-semibold"> Observaciones (*) </label>
                                <textarea class="form-control" name="observaciones" id="observaciones" rows="3" required></textarea>
                                <div class="form-text"> 
                                    Campo obligatorio.
                                </div>
                            </div>

                            <div class="col-12 my-4">
                                <hr class="mb-0 border border-1 border-dark">
                            </div>
                        </div>

                        <!--Botón de registro de contingencia-->
                        <div class="text-center">
                            <button class="btn btn-primary">
                                Registrar contingencia
                            </button>
                        </div>
                    </form>
                    <?php
                    }
                    else {
                    ?>
                        <h4 class="text-center mt-4 mb-0">
                            <span class="badge bg-danger py-3">
                                No se pueden añadir contingencias si no existen colaboradores registrados
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
        <script src="../../js/peticiones_ajax/verificar_contingencia.js"> </script>
        <script src="../../js/verificar_rangos_horas.js"> </script>
        <script type="text/javascript">
            document.body.onload = () => {
                document.querySelector("html").classList.remove("invisible");
            }

            <?php
                if(isset($colaboradores) && $colaboradores->num_rows > 0) {
                ?>
                    document.body.onload = () => {
                        document.querySelector("html").classList.remove("invisible");
                        verificarContingencia(document.getElementById('colaboradores').value, 
                        document.getElementById('fecha-registro').value, 'fecha-registro', 1)
                    }
                <?php
                }
                else {
                ?>
                    document.body.onload = () => {
                        document.querySelector("html").classList.remove("invisible");
                    }
                <?php
                }
            ?>

            document.getElementById("cierre-sesion").addEventListener("click", () => {
                document.getElementById("formulario").requestSubmit();
            });
            document.getElementById("formulario").addEventListener("submit", confirmarCierreSesion);

            <?php
                if(isset($colaboradores) && $colaboradores->num_rows > 0) {
                ?>
                    document.getElementById("registro-contingencia").addEventListener("submit", confirmarRegistroContingencia);
                    document.getElementById("fecha-registro").value = new Date().toISOString().substring(0, 10);
                    dselect(document.getElementById("colaboradores"), { search: true, maxHeight: "200px" });
                <?php
                }
            ?>
        </script>
    </body>
</html>