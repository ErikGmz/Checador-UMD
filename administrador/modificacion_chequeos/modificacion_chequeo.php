<?php
    session_start();

    # Verificar si algún administrador no
    # ha iniciado su correspondiente sesión.
    if(!isset($_SESSION["ID_administrador"])) {
        header("location: ../menu_principal/menu_administrador.php");
        die();
    }

    # Definir la zona horaria.
    date_default_timezone_set('America/Mexico_City');

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
        <link rel="stylesheet" href="../../css/clases_globales.css">
        <link rel="stylesheet" href="../../css/desplazamiento_instantaneo.css">
        <link rel="stylesheet" href="../../css/estilos_administradores.css">
        <link rel="stylesheet" href="../../css/bootstrap/bootstrap-icons.css">
        <link rel="stylesheet" href="../../css/bootstrap/bootstrap.min.css">
        <link rel="stylesheet" href="../../css/dselect.min.css">
        
        <!--Título de la página-->
        <title> Modificación de chequeo </title>

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
                        Modificación de chequeo del sistema
                    </h1>
                    <hr class="my-4 border border-1 border-dark">

                    <!--Formulario de selección del chequeo registrado-->
                    <?php
                    if(isset($colaboradores) && $colaboradores->num_rows > 0) {
                    ?>
                    <form method="GET" action="<?=$_SERVER['PHP_SELF']?>" class="mb-0">
                        <h4 class="mb-4 text-center"> Datos del chequeo </h4>
                        <div class="row">
                            <!--Selección del número de chequeo-->
                            <div class="col-12 mb-4 texto-colaborador">
                                <label for="numeros-chequeos" class="form-label fw-semibold"> Número de chequeo (*) </label>
                                <select class="form-select recuadro-ID text-start" name="numero-chequeo" id="numero-chequeo" required> 
                                    <option selected value="-1"> Chequeos no encontrados </option>
                                </select>
                            </div>

                            <!--Selección del colaborador-->
                            <div class="col-12 col-md-6 mb-4">
                                <label for="colaboradores" class="form-label fw-semibold"> Colaborador (*) </label>
                                <select class="form-select" name="ID-colaborador" id="colaboradores" required
                                onchange="obtenerNumeroChequeos(document.getElementById('colaboradores').value, 
                                document.getElementById('fecha-chequeo').value, 'fecha-chequeo', 'numero-chequeo', 1)">
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

                            <!--Selección de la fecha de chequeo-->
                            <div class="col-12 col-md-6 mb-4">
                                <label for="fecha-chequeo" class="form-label fw-semibold"> Fecha de chequeo (*) </label>
                                <input type="date" name="fecha-chequeo" value="2021-01-01" min="2021-01-01" max="2030-12-30" 
                                class="form-control" id="fecha-chequeo" autocomplete="OFF" required
                                onchange="obtenerNumeroChequeos(document.getElementById('colaboradores').value, 
                                document.getElementById('fecha-chequeo').value, 'fecha-chequeo', 'numero-chequeo', 1)">
                            </div>
                        </div>

                        <div class="text-center">
                            <button class="btn btn-primary">
                                Cargar datos
                            </button>
                        </div>
                    </form>

                    <?php
                    if(isset($_GET["ID-colaborador"], $_GET["fecha-chequeo"], $_GET["numero-chequeo"])) {
                        # Buscar el chequeo introducido 
                        # en la información de búsqueda.
                        if($chequeo = $conexion_base->query("SELECT TIME_FORMAT(hora_inicial, '%H:%i:%s') AS hora_inicial,
                        TIME_FORMAT(hora_final, '%H:%i:%s') AS hora_final, bloqueo_registro FROM chequeo WHERE ID_colaborador = 
                        '" . $_GET["ID-colaborador"] . "' AND fecha_chequeo = '" . $_GET["fecha-chequeo"] 
                        . "' AND numero_chequeo = " . $_GET["numero-chequeo"] . ";")) {
                            if($chequeo->num_rows > 0) {
                                $datos_chequeo = $chequeo->fetch_row();
                                $valido = true;
                                ?>
                                <hr class="my-4 border border-1 border-dark">
                                <?php
                    ?>
                    <form method="POST" action="procesar_modificacion_chequeo.php" 
                    class="mb-0" id="modificacion-chequeo">
                        <div class="row mb-2">
                            <!--Datos del chequeo-->
                            <div class="col-12 text-center mb-4">
                                <h4 class="mb-0"> Modificación de datos </h4>
                            </div>

                            <!--Selección del número de chequeo-->
                            <div class="col-12 mb-4 texto-colaborador">
                                <div>
                                    <label for="numeros-chequeos" class="form-label fw-semibold"> Número de chequeo (*) </label>
                                    <select class="form-select recuadro-ID text-start" name="numero-chequeo" id="nuevo-numero-chequeo" required
                                    onchange="verificarChequeo(document.getElementById('nuevos-colaboradores').value, 
                                    document.getElementById('nueva-fecha-chequeo').value, 'nueva-fecha-chequeo', 
                                    'texto-hora-inicial', 'texto-hora-final', 'hora-inicial', 'hora-final', 2, 
                                    document.getElementById('nuevo-numero-chequeo').value)"> 
                                        <option selected value="-1"> Chequeos no encontrados </option>
                                    </select>
                                </div>
                                
                                <div class="form-text"> 
                                    Campo obligatorio. 
                                </div>
                            </div>

                            <!--Selección del colaborador-->
                            <div class="col-12 col-md-6 mb-4">
                                <label for="colaboradores" class="form-label fw-semibold"> Colaborador (*) </label>
                                <select class="form-select" name="ID-colaborador" id="nuevos-colaboradores" required>
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
                                    Campo obligatorio.
                                </div>
                            </div>

                            <!--Selección de la fecha de chequeo-->
                            <div class="col-12 col-md-6 mb-4">
                                <label for="fecha-chequeo" class="form-label fw-semibold"> Fecha de chequeo (*) </label>
                                <input type="date" name="fecha-chequeo" value="<?=$_GET["fecha-chequeo"]?>" min="2021-01-01" max="2030-12-30" 
                                class="form-control" id="nueva-fecha-chequeo" autocomplete="OFF" required>
                                <div class="form-text"> 
                                    Campo obligatorio. El rango de fechas debe 
                                    encontrarse entre 01-01-2021 y 30-12-2030.
                                </div>
                            </div>

                            <!--Solicitud de hora inicial-->
                            <div class="col-12 col-md-6 mb-4 mb-md-0">
                                <label for="hora-inicial" class="form-label fw-semibold"> Hora de entrada (*) </label>
                                <input type="time" class="form-control" id="hora-inicial" step="1"
                                autocomplete="OFF" required name="hora-inicial" value="<?=$datos_chequeo[0]?>"
                                oninput="verificarRangosHoras('hora-inicial', 'hora-final')">
                                <div class="form-text" id="texto-hora-inicial"> 
                                    Campo obligatorio. Formato de 00:00:00 a 24:00:00 horas.
                                </div>
                            </div>

                            <!--Solicitud de hora final-->
                            <div class="col-12 col-md-6 mb-4 ">
                                <label for="hora-final" class="form-label fw-semibold"> Hora de salida </label>
                                <input type="time" class="form-control" id="hora-final" step="1"
                                autocomplete="OFF" name="hora-final" value="<?=$datos_chequeo[1]?>"
                                oninput="verificarRangosHoras('hora-inicial', 'hora-final')">
                                <div class="form-text" id="texto-hora-final"> 
                                    Campo opcional. Formato de 00:00:00 a 24:00:00 horas.
                                </div>
                            </div>

                            <!--Selección del estado del chequeo-->
                            <div class="col-12 col-md-6 mb-0">
                                <label for="estado-chequeo" class="form-label fw-semibold"> Estado del chequeo (*) </label>
                                <select class="form-select" name="estado-chequeo" id="estado-chequeo" required>
                                    <option value="0" <?php if(!$datos_chequeo[2]) echo "selected" ?>> Desbloqueado </option>
                                    <option value="1" <?php if($datos_chequeo[2]) echo "selected" ?>> Bloqueado </option>
                                </select>
                                <div class="form-text"> 
                                    Campo obligatorio.
                                </div>
                            </div>

                            <div class="col-12 my-4">
                                <hr class="mb-0 border border-1 border-dark">
                            </div>
                        </div>

                        <!--Botón de registro de chequeo-->
                        <div class="text-center">
                            <button class="btn btn-primary">
                                Modificar chequeo
                            </button>
                        </div>
                        <input type="hidden" id="anterior-ID-colaborador" name="anterior-ID-colaborador" value="<?=$_GET["ID-colaborador"]?>">
                        <input type="hidden" id="fecha-anterior" name="fecha-anterior" value="<?=$_GET["fecha-chequeo"]?>">
                    </form>
                    <?php
                                }
                                else {
                                    ?>
                                        <hr class="my-4 border border-1 border-dark">
                                        <h4 class="text-center mt-4 mb-0">
                                            <span class="badge bg-danger py-3">
                                                El chequeo no fue encontrado
                                            </span>
                                        </h4>
                                    <?php
                                }
                            }
                            else {
                                ?>
                                    <hr class="my-4 border border-1 border-dark">
                                    <h4 class="text-center mt-4 mb-0">
                                        <span class="badge bg-danger py-3">
                                            El chequeo no fue encontrado
                                        </span>
                                    </h4>
                                <?php
                            }
                            @$chequeo->close();
                        }
                    }
                    else {
                    ?>
                        <h4 class="text-center mt-4 mb-0">
                            <span class="badge bg-danger py-3">
                                No hay contingencias a modificar si no existen colaboradores registrados
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
        <script src="../../js/peticiones_ajax/verificar_chequeo.js"> </script>
        <script src="../../js/peticiones_ajax/obtener_numeros_chequeos.js"> </script>
        <script src="../../js/verificar_rangos_horas.js"> </script>
        <script type="text/javascript">
            document.body.onload = () => {
                document.querySelector("html").classList.remove("invisible");
            }

            document.getElementById("cierre-sesion").addEventListener("click", () => {
                document.getElementById("formulario").requestSubmit();
            });
            document.getElementById("formulario").addEventListener("submit", confirmarCierreSesion);

            <?php
            if(isset($colaboradores) && $colaboradores->num_rows > 0) {
                if(!isset($_GET["ID-colaborador"], $_GET["fecha-chequeo"])) {
                ?>
                    document.getElementById("fecha-chequeo").value = new Date().toLocaleDateString("fr-CA");
                <?php
                }
                else {
                ?>
                    document.getElementById("fecha-chequeo").value = "<?=date("Y-m-d", strtotime($_GET["fecha-chequeo"]))?>";
                <?php
                }
                ?>
                dselect(document.getElementById("numero-chequeo"), { maxHeight: "200px" });
                dselect(document.getElementById("colaboradores"), { search: true, maxHeight: "200px" });
                <?php
                if(isset($_GET["ID-colaborador"], $valido) && @$valido) {
                ?>
                    obtenerNumeroChequeos(document.getElementById("colaboradores").value, 
                    document.getElementById("fecha-chequeo").value, "fecha-chequeo", "numero-chequeo", <?php echo $_GET["numero-chequeo"] ?>);
                    obtenerNumeroChequeos(document.getElementById('nuevos-colaboradores').value, 
                    document.getElementById('nueva-fecha-chequeo').value, 'nueva-fecha-chequeo', 'nuevo-numero-chequeo', <?php echo $_GET["numero-chequeo"] ?>)
                    .then(function(respuesta) {
                        verificarChequeo(document.getElementById('nuevos-colaboradores').value, 
                        document.getElementById('nueva-fecha-chequeo').value, 'nueva-fecha-chequeo', 
                        'texto-hora-inicial', 'texto-hora-final', 'hora-inicial', 'hora-final', 2, 
                        <?php echo $_GET["numero-chequeo"] ?>);
                    });

                    document.getElementById("modificacion-chequeo").addEventListener("submit", confirmarModificacionChequeo);
                    document.getElementById("fecha-chequeo").value = "<?=date("Y-m-d", strtotime($_GET["fecha-chequeo"]))?>";
                
                    dselect(document.getElementById("nuevo-numero-chequeo"), { maxHeight: "200px" });
                    dselect(document.getElementById("nuevos-colaboradores"), { search: true, maxHeight: "200px" });
                    dselect(document.getElementById("estado-chequeo"), { maxHeight: "200px" });

                    document.getElementById("nuevos-colaboradores").addEventListener("change", () => {
                        obtenerNumeroChequeos(document.getElementById('nuevos-colaboradores').value, 
                        document.getElementById('nueva-fecha-chequeo').value, 'nueva-fecha-chequeo', 'nuevo-numero-chequeo', -1)
                        .then(function(respuesta) {
                            verificarChequeo(document.getElementById('nuevos-colaboradores').value, 
                            document.getElementById('nueva-fecha-chequeo').value, 'nueva-fecha-chequeo',
                            'texto-hora-inicial', 'texto-hora-final', 'hora-inicial', 'hora-final', 2, document.getElementById("nuevo-numero-chequeo").value);
                        });
                    });

                    document.getElementById("nueva-fecha-chequeo").addEventListener("change", () => {
                        obtenerNumeroChequeos(document.getElementById('nuevos-colaboradores').value, 
                        document.getElementById('nueva-fecha-chequeo').value, 'nueva-fecha-chequeo', 'nuevo-numero-chequeo', -1)
                        .then(function(respuesta) {
                            verificarChequeo(document.getElementById('nuevos-colaboradores').value, 
                            document.getElementById('nueva-fecha-chequeo').value, 'nueva-fecha-chequeo',
                            'texto-hora-inicial', 'texto-hora-final', 'hora-inicial', 'hora-final', 2, document.getElementById("nuevo-numero-chequeo").value);
                        });
                    });
                <?php
                }
                else {
                ?>
                    obtenerNumeroChequeos(document.getElementById("colaboradores").value, 
                    document.getElementById("fecha-chequeo").value, "fecha-chequeo", "numero-chequeo", -1);
                <?php
                }
            }
            ?>
        </script>
    </body>
</html>