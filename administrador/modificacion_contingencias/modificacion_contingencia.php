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
<html lang="es" class="d-none invisible">
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
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.9.1/font/bootstrap-icons.css">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-gH2yIJqKdNHPEq0n4Mqa/HGKIhSkIHeL5AyhkYV8i59U5AR6csBvApHHNl/vI1Bx" crossorigin="anonymous">
        <link rel="stylesheet" href="../../css/dselect.min.css">
        
        <!--Título de la página-->
        <title> Modificación de contingencia </title>

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
                        Modificación de contingencia del sistema
                    </h1>
                    <hr class="my-4 border border-1 border-dark">

                    <!--Formulario de selección de la contingencia registrada-->
                    <form method="GET" action="<?=$_SERVER['PHP_SELF']?>" class="mb-0">
                        <h4 class="mb-4 text-center"> Datos de la contingencia </h4>
                        <div class="row">
                            <!--Selección del colaborador-->
                            <div class="col-12 col-md-6 mb-4">
                                <label for="colaboradores" class="form-label fw-semibold"> Colaborador (*) </label>
                                <select class="form-select" name="ID-colaborador" id="colaboradores" required>
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
                            </div>

                            <!--Selección de la fecha de contingencia-->
                            <div class="col-12 col-md-6 mb-4">
                                <label for="fecha-registro" class="form-label fw-semibold"> Fecha de contingencia (*) </label>
                                <input type="date" name="fecha-registro" value="2021-01-01" min="2021-01-01" max="2030-12-30" 
                                class="form-control" id="fecha-registro" autocomplete="OFF" required>
                            </div>
                        </div>

                        <div class="text-center">
                            <button class="btn btn-primary">
                                Cargar datos
                            </button>
                        </div>
                    </form>

                    <?php
                    if(isset($_GET["ID-colaborador"], $_GET["fecha-registro"])) {
                        # Buscar la contingencia introducida 
                        # en la información de búsqueda.
                        if($contingencia = $conexion_base->query("SELECT TIME_FORMAT(hora_inicial, '%H:%i') AS hora_inicial,
                        TIME_FORMAT(hora_final, '%H:%i') AS hora_final, observaciones FROM contingencia WHERE ID_colaborador = 
                        '" . $_GET["ID-colaborador"] . "' AND fecha = '" . $_GET["fecha-registro"] . "';")) {
                            if($contingencia->num_rows > 0) {
                                $datos_contingencia = $contingencia->fetch_row();
                                $valido = true;
                                ?>
                                <hr class="my-4 border border-1 border-dark">
                                <?php
                    ?>
                    <form method="POST" action="procesar_modificacion_contingencia.php" 
                    class="mb-0" id="modificacion-contingencia">
                        <div class="row mb-2">
                            <!--Datos de la contingencia-->
                            <div class="col-12 text-center mb-4">
                                <h4 class="mb-0"> Modificación de datos </h4>
                            </div>

                            <!--Selección del colaborador-->
                            <div class="col-12 col-md-6 mb-4">
                                <label for="colaboradores" class="form-label fw-semibold"> Colaborador (*) </label>
                                <select class="form-select" name="ID-colaborador" id="nuevos-colaboradores" 
                                onchange="verificarContingencia(document.getElementById('nuevos-colaboradores').value, 
                                document.getElementById('nueva-fecha-registro').value, 'nueva-fecha-registro', 2)" required>
                                    <?php
                                        # Obtener todos los colaboradores registrados.
                                        $colaboradores = $conexion_base->query("SELECT ID, CONCAT_WS(' ', nombres, apellido_paterno, apellido_materno) 
                                        AS nombre_completo FROM `colaborador`;");

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
                                <input type="date" name="fecha-registro" value="<?=$_GET["fecha-registro"]?>" min="2021-01-01" max="2030-12-30" 
                                class="form-control" id="nueva-fecha-registro" autocomplete="OFF" required
                                onchange="verificarContingencia(document.getElementById('nuevos-colaboradores').value, 
                                document.getElementById('nueva-fecha-registro').value, 'nueva-fecha-registro', 2)">
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
                                autocomplete="OFF" required name="hora-inicial" placeholder="08:00" value="<?=$datos_contingencia[0]?>"
                                pattern="^((0[8-9]|1[0-9]|2[0]):[0-5][0-9])|21:00$"
                                oninput="verificarRangosHoras('hora-inicial', 'hora-final', 1)">
                                <div class="form-text"> 
                                    Formato de 08:00 a 21:00 horas.
                                </div>
                            </div>

                            <!--Solicitud de hora final-->
                            <div class="col-12 col-md-6 mb-4">
                                <label for="hora-final" class="form-label fw-semibold"> Hora de salida (*) </label>
                                <input type="text" class="form-control" id="hora-final" 
                                autocomplete="OFF" required name="hora-final" placeholder="12:00" value="<?=$datos_contingencia[1]?>"
                                pattern="^((0[8-9]|1[0-9]|2[0]):[0-5][0-9])|21:00$"
                                oninput="verificarRangosHoras('hora-inicial', 'hora-final', 2)">
                                <div class="form-text"> 
                                    Formato de 08:00 a 21:00 horas.
                                </div>
                            </div>

                            <!--Solicitud de observaciones-->
                            <div class="col-12">
                                <label for="observaciones" class="form-label fw-semibold"> Observaciones (*) </label>
                                <textarea class="form-control" name="observaciones" id="observaciones" rows="3" required><?php echo $datos_contingencia[2] ?></textarea>
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
                                Modificar contingencia
                            </button>
                        </div>
                        <input type="hidden" id="anterior-ID-colaborador" name="anterior-ID-colaborador" value="<?=$_GET["ID-colaborador"]?>">
                        <input type="hidden" id="fecha-anterior" name="fecha-anterior" value="<?=$_GET["fecha-registro"]?>">
                    </form>
                    <?php
                            }
                            else {
                                ?>
                                    <hr class="my-4 border border-1 border-dark">
                                    <h4 class="text-center mt-4">
                                        <span class="badge bg-danger py-3">
                                            La contingencia no fue encontrada
                                        </span>
                                    </h4>
                                <?php
                            }
                        }
                        else {
                            ?>
                                <hr class="my-4 border border-1 border-dark">
                                <h4 class="text-center mt-4">
                                    <span class="badge bg-danger py-3">
                                        La contingencia no fue encontrada
                                    </span>
                                </h4>
                            <?php
                        }
                        @$contingencia->close();
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
        <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"> </script>
        <script src="../../js/confirmar_operaciones.js"> </script>
        <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.5/dist/umd/popper.min.js" integrity="sha384-Xe+8cL9oJa6tN/veChSP7q+mnSPaj5Bcu9mPX5F5xIGE0DVittaqT5lorf0EI7Vk" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.min.js" integrity="sha384-ODmDIVzN+pFdexxHEHFBQH3/9/vQ9uori45z4JjnFsRydbmQbmL5t1tQ0culUzyK" crossorigin="anonymous"></script>
        <script src="../../js/dselect.min.js"> </script>
        <script src="../../js/peticiones_ajax/verificar_contingencia.js"> </script>
        <script src="../../js/verificar_rangos_horas.js"> </script>
        <script type="text/javascript">
            // Mostrar el contenido una vez que la
            // página se cargue por completo.
            window.onload = () => {
                document.querySelector("html").classList.remove("d-none");
                setTimeout(() => {
                    document.querySelector("html").classList.remove("invisible");

                    verificarContingencia(document.getElementById('nuevos-colaboradores').value, 
                    document.getElementById('nueva-fecha-registro').value, 'nueva-fecha-registro', 2)
                }, 20);
            }

            document.getElementById("cierre-sesion").addEventListener("click", () => {
                document.getElementById("formulario").requestSubmit();
            });
            document.getElementById("formulario").addEventListener("submit", confirmarCierreSesion);
            document.getElementById("fecha-registro").value = new Date().toISOString().substring(0, 10);
            dselect(document.getElementById("colaboradores"), { search: true, maxHeight: "200px" });

            <?php
            if(isset($_GET["ID-colaborador"], $_GET["fecha-registro"], $valido) && @$valido) {
            ?>
            document.getElementById("modificacion-contingencia").addEventListener("submit", confirmarModificacionContingencia);
            dselect(document.getElementById("nuevos-colaboradores"), { search: true, maxHeight: "200px" });
            <?php
            }
            ?>
        </script>
    </body>
</html>