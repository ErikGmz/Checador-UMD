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
        <link rel="stylesheet" href="../../css/clases_globales.css">
        <link rel="stylesheet" href="../../css/desplazamiento_instantaneo.css">
        <link rel="stylesheet" href="../../css/estilos_administradores.css">
        <link rel="stylesheet" href="../../css/bootstrap/bootstrap-icons.css">
        <link rel="stylesheet" href="../../css/bootstrap/bootstrap.min.css">
        <link rel="stylesheet" href="../../css/dselect.min.css">
        
        <!--Título de la página-->
        <title> Eliminación de chequeo </title>

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
                        Eliminación de chequeo del sistema
                    </h1>
                    <hr class="my-4 border border-1 border-dark">

                    <!--Formulario de selección del chequeo registrada-->
                    <?php
                    if(isset($colaboradores) && $colaboradores->num_rows > 0) {
                    ?>
                    <form method="POST" action="procesar_eliminacion_chequeo.php" id="eliminacion-chequeo" class="mb-0">
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
                                <input type="date" name="fecha-chequeo" value="2021-01-01" min="2021-01-01"
                                class="form-control" id="fecha-chequeo" autocomplete="OFF" required
                                onchange="obtenerNumeroChequeos(document.getElementById('colaboradores').value, 
                                document.getElementById('fecha-chequeo').value, 'fecha-chequeo', 'numero-chequeo', 1)">
                            </div>
                        </div>

                        <div class="text-center">
                            <button class="btn btn-primary">
                                Eliminar chequeo
                            </button>
                        </div>
                    </form>
                    <?php
                    }
                    else {
                    ?>
                        <h4 class="text-center mt-4 mb-0">
                            <span class="badge bg-danger py-3">
                                No hay chequeos a eliminar si no existen colaboradores registrados
                            </span>
                        </h4>
                    <?php
                    }
                    ?>
                </div>
            </div>
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
            <?php
                if(isset($colaboradores) && $colaboradores->num_rows > 0) {
                ?>
                    document.body.onload = () => {
                        document.querySelector("html").classList.remove("invisible");
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
                    document.getElementById("fecha-chequeo").value = new Date().toLocaleDateString("fr-CA");
                    document.getElementById("eliminacion-chequeo").addEventListener("submit", confirmarEliminacionChequeo);
                    dselect(document.getElementById("colaboradores"), { search: true, maxHeight: "200px" });
                    dselect(document.getElementById("numero-chequeo"), { maxHeight: "200px" });
                    
                    obtenerNumeroChequeos(document.getElementById("colaboradores").value, 
                    document.getElementById("fecha-chequeo").value, "fecha-chequeo", "numero-chequeo", -1);
                    <?php
                }
            ?>
        </script>
    </body>
</html>