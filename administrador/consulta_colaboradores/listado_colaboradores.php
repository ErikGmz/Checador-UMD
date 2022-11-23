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

    # Obtener todas las carreras registradas.
    $carreras = $conexion_base->query("SELECT * FROM carrera;");

    # Obtener todas las modalidades registradas.
    $modalidades = $conexion_base->query("SELECT * FROM modalidad;");

    # Obtener todas los tipos de participación registrados.
    $participaciones = $conexion_base->query("SELECT * FROM participacion;");

    # Obtener turnos registrados.
    $turnos = $conexion_base->query("SELECT * FROM turno;");
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
        <link rel="stylesheet" href="../../css/dselect.min.css">

        <!--Título de la página-->
        <title> Listado de colaboradores </title>

        <!--Ícono de la página-->
        <link rel="apple-touch-icon" sizes="76x76" href="../../favicon/apple-touch-icon.png">
        <link rel="icon" type="image/png" sizes="32x32" href="../../favicon/favicon-32x32.png">
        <link rel="icon" type="image/png" sizes="16x16" href="../favicon/favicon-16x16.png">
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
                        Listado de colaboradores del sistema
                    </h1>
                    <hr class="my-4 border border-1 border-dark">

                    <?php
                    if(isset($colaboradores) && @$colaboradores->num_rows > 0) {
                    ?>
                    <form method="GET" action="<?=$_SERVER['PHP_SELF']?>" class="mb-0 px-0">
                        <h4 class="mb-4 text-center"> Datos para el filtrado del listado </h4>
                        <div class="row">
                            <!--Selección del colaborador-->
                            <div class="col-12 col-md-6 mb-4">
                                <label for="colaboradores" class="form-label fw-semibold"> Colaborador </label>
                                <select class="form-select" name="ID-colaborador" id="colaboradores" required>
                                    <?php
                                        if(isset($_GET["ID-colaborador"])) {
                                            echo "<option value='-1'> Sin seleccionar </option>";
                                        }
                                        else {
                                            echo "<option selected value='-1'> Sin seleccionar </option>";
                                        }

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
                                    Campo opcional.
                                </div>
                            </div>

                            <!--Selección de la carrera-->
                            <div class="col-12 col-md-6 mb-4">
                                <label for="carreras" class="form-label fw-semibold"> Carrera </label>
                                <select class="form-select" name="ID-carrera" id="carreras" required>
                                    <?php
                                        if(isset($_GET["ID-carrera"])) {
                                            echo "<option value='-1'> Sin seleccionar </option>";
                                        }
                                        else {
                                            echo "<option selected value='-1'> Sin seleccionar </option>";
                                        }

                                        if(isset($carreras) && $carreras->num_rows > 0) {
                                            while($carrera = $carreras->fetch_row()) {
                                                if(@$_GET["ID-carrera"] == $carrera[0]) {
                                                    echo "<option selected value='" . $carrera[0]. "'> " . $carrera[1] . " </option> ";
                                                }
                                                else {
                                                    echo "<option value='" . $carrera[0]. "'> " . $carrera[1] . " </option> ";
                                                }
                                            }
                                        }
                                    ?>
                                </select>
                                <div class="form-text"> 
                                    Campo opcional.
                                </div>
                            </div>

                            <!--Selección de la modalidad-->
                            <div class="col-12 col-md-6 mb-4">
                                <label for="modalidades" class="form-label fw-semibold"> Modalidad </label>
                                <select class="form-select" name="ID-modalidad" id="modalidades">
                                    <?php
                                        if(isset($_GET["ID-modalidad"])) {
                                            echo "<option value='-1'> Sin seleccionar </option>";
                                        }
                                        else {
                                            echo "<option selected value='-1'> Sin seleccionar </option>";
                                        }

                                        if(isset($modalidades) && $modalidades->num_rows > 0) {
                                            while($modalidad = $modalidades->fetch_row()) {
                                                if(@$_GET["ID-modalidad"] == $modalidad[0]) {
                                                    echo "<option selected value='" . $modalidad[0]. "'> " . $modalidad[1] . " </option> ";
                                                }
                                                else {
                                                    echo "<option value='" . $modalidad[0]. "'> " . $modalidad[1] . " </option> ";
                                                }
                                            }
                                        }
                                    ?>
                                </select>
                                <div class="form-text"> 
                                    Campo opcional.
                                </div>
                            </div>

                            <!--Selección del tipo de participación-->
                            <div class="col-12 col-md-6 mb-4">
                                <label for="participaciones" class="form-label fw-semibold"> Tipo de participación </label>
                                <select class="form-select" name="ID-participacion" id="participaciones">
                                    <?php
                                        if(isset($_GET["ID-participacion"])) {
                                            echo "<option value='-1'> Sin seleccionar </option>";
                                        }
                                        else {
                                            echo "<option selected value='-1'> Sin seleccionar </option>";
                                        }

                                        if(isset($participaciones) && $participaciones->num_rows > 0) {
                                            while($participacion = $participaciones->fetch_row()) {
                                                if(@$_GET["ID-participacion"] == $participacion[0]) {
                                                    echo "<option selected value='" . $participacion[0]. "'> " . $participacion[1] . " </option> ";
                                                }
                                                else {
                                                    echo "<option value='" . $participacion[0]. "'> " . $participacion[1] . " </option> ";
                                                }
                                            }
                                        }
                                    ?>
                                </select>
                                <div class="form-text"> 
                                    Campo opcional.
                                </div>
                            </div>

                            <!--Selección del turno-->
                            <div class="col-12 col-md-6 mb-4">
                                <label for="turnos" class="form-label fw-semibold"> Turno </label>
                                <select class="form-select" name="ID-turno" id="turnos">
                                    <?php
                                        if(isset($_GET["ID-turno"])) {
                                            echo "<option value='-1'> Sin seleccionar </option>";
                                        }
                                        else {
                                            echo "<option selected value='-1'> Sin seleccionar </option>";
                                        }

                                        if(isset($turnos) && $turnos->num_rows > 0) {
                                            while($turno = $turnos->fetch_row()) {
                                                if(@$_GET["ID-turno"] == $turno[0]) {
                                                    echo "<option selected value='" . $turno[0]. "'> " . $turno[1] . " </option> ";
                                                }
                                                else {
                                                    echo "<option value='" . $turno[0]. "'> " . $turno[1] . " </option> ";
                                                }
                                            }
                                        }
                                    ?>
                                </select>
                                <div class="form-text"> 
                                    Campo opcional.
                                </div>
                            </div>
                        </div>

                        <div class="text-center">
                            <button class="btn btn-primary">
                                Cargar datos
                            </button>
                        </div>
                    </form>

                    <?php
                    if(isset($_GET["ID-colaborador"], $_GET["ID-carrera"], $_GET["ID-modalidad"],
                    $_GET["ID-participacion"], $_GET["ID-turno"])) {
                        # Verificar si se aplicarán o no filtros al listado de colaboradores.
                        $aplicacion_filtros = $_GET["ID-colaborador"] != -1 || $_GET["ID-carrera"] != -1 ||
                        $_GET["ID-modalidad"] != -1 || $_GET["ID-participacion"] != -1 || $_GET["ID-turno"] != -1;

                        # Activar o no las estadísticas según la presencia de filtros en el listado.
                        if(!$aplicacion_filtros) {
                            # Obtener todos los colaboradores del sistema.
                            $colaboradores_desglosados = $conexion_base->query("SELECT * FROM desglose_colaboradores;");

                            # Obtener el conteo de cuántos colaboradores hay por carrera.
                            $estadisticas_carreras = $conexion_base->query("SELECT * FROM cantidad_colaboradores_carreras ORDER BY cantidad_colaboradores;");

                            # Obtener el conteo de cuántos colaboradores hay por modalidad.
                            $estadisticas_modalidades = $conexion_base->query("SELECT * FROM cantidad_colaboradores_modalidades ORDER BY cantidad_colaboradores;");

                            # Obtener el conteo de cuántos colaboradores hay por tipo de participación.
                            $estadisticas_participaciones = $conexion_base->query("SELECT * FROM cantidad_colaboradores_participaciones ORDER BY cantidad_colaboradores;");

                            # Obtener el conteo de cuántos colaboradores hay por turno.
                            $estadisticas_turnos = $conexion_base->query("SELECT * FROM cantidad_colaboradores_turnos ORDER BY cantidad_colaboradores;");
                        }
                        else {
                            if($_GET["ID-colaborador"] != -1) {
                                $colaboradores_desglosados = $conexion_base->query("SELECT * FROM desglose_colaboradores
                                WHERE ID = " . $_GET["ID-colaborador"] . ";");
                            }
                            else {
                                # Estructurar la consulta con base en los parámetros de filtrado.
                                $consulta = "";

                                if($_GET["ID-carrera"] != -1) {
                                    $consulta = "SELECT * FROM desglose_colaboradores 
                                    WHERE ID_carrera = " . $_GET["ID-carrera"];
                                }

                                if($_GET["ID-modalidad"] != -1) {
                                    if(empty($consulta)) {
                                        $consulta = "SELECT * FROM desglose_colaboradores 
                                        WHERE ID_modalidad = " . $_GET["ID-modalidad"];
                                    }
                                    else {
                                        $consulta .= " AND ID_modalidad = " . $_GET["ID-modalidad"];
                                    }
                                }

                                if($_GET["ID-participacion"] != -1) {
                                    if(empty($consulta)) {
                                        $consulta = "SELECT * FROM desglose_colaboradores 
                                        WHERE ID_participacion = " . $_GET["ID-participacion"];
                                    }
                                    else {
                                        $consulta .= " AND ID_participacion = " . $_GET["ID-participacion"];
                                    }
                                }

                                if($_GET["ID-turno"] != -1) {
                                    if(empty($consulta)) {
                                        $consulta = "SELECT * FROM desglose_colaboradores 
                                        WHERE ID_turno = " . $_GET["ID-turno"];
                                    }
                                    else {
                                        $consulta .= " AND ID_turno = " . $_GET["ID-turno"];
                                    }
                                }
                                $colaboradores_desglosados = $conexion_base->query($consulta);
                            }
                        }
                    ?>
                    <hr class="my-4 border border-1 border-dark">
                    <div class="col-12 text-center mb-4">
                        <h4 class="mb-0"> Resultados del listado especificado </h4>
                    </div>

                    <!--Listado general de los colaboradores obtenidos--->
                    <div class="rounded-2 text-center fondo-pantone-azul-intermedio mx-auto <?php if(!$aplicacion_filtros) echo "mb-4" ?>"> 
                        <h6 class="text-center text-white fondo-pantone-azul-oscuro py-3 rounded-top mb-0 px-3"> 
                            Listado de colaboradores
                        </h6>

                        <div class="table-responsive fondo-pantone-azul-intermedio rounded p-3 tabla-desglose">
                            <table class="table table-striped mb-0">
                                <thead>
                                    <tr">
                                        <th scope="col"> ID </th>
                                        <th scope="col"> Nombre completo </th>
                                        <th scope="col"> Fecha de nacimiento </th>
                                        <th scope="col"> Carrera </th>
                                        <th scope="col"> Modalidad </th>
                                        <th scope="col"> Participación </th>
                                        <th scope="col" class="no-partir-palabra"> Hora de entrada </th>
                                        <th scope="col" class="no-partir-palabra"> Hora de salida </th>
                                        <th scope="col"> Turno </th>
                                    </tr>
                                </thead>

                                <tbody class="table-group-divider">
                                    <tr>
                                        <?php
                                        if(isset($colaboradores_desglosados) && $colaboradores_desglosados->num_rows > 0) {
                                            while($colaborador = $colaboradores_desglosados->fetch_row()) {
                                                echo "<tr> ";
                                                echo "<th scope='row' class='py-3'> " . $colaborador[0] . " </th> ";
                                                echo "<td class='py-3'> " . $colaborador[1] .  " </td> ";
                                                echo "<td class='py-3'> " . ((empty($colaborador[2])) ? "N/A" : date("d-m-Y", strtotime($colaborador[2]))) .  " </td> ";
                                                echo "<td class='py-3'> " . $colaborador[5] .  " </td> ";
                                                echo "<td class='py-3'> " . $colaborador[6] . " </td> ";
                                                echo "<td class='py-3'> " . $colaborador[9] . " </td> ";
                                                echo "<td class='py-3'> " . date("h:i A", strtotime($colaborador[7])) . " </td> ";
                                                echo "<td class='py-3'> " . date("h:i A", strtotime($colaborador[8])) . " </td>";
                                                echo "<td class='py-3'> " . $colaborador[10] . " </td>";
                                                echo " </tr>";
                                            }
                                        }
                                        ?>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <?php
                    if(!$aplicacion_filtros) {
                    ?>
                    <!--Estadísticas generales sobre las carreras de los colaboradores--->
                    <div class="rounded-2 text-center fondo-pantone-azul-intermedio mx-auto mb-4"> 
                        <h6 class="text-center text-white fondo-pantone-azul-oscuro py-3 rounded-top mb-0 px-3"> 
                            Cantidad de colaboradores por carrera
                        </h6>

                        <div class="table-responsive fondo-pantone-azul-intermedio rounded p-3 tabla-desglose">
                            <table class="table table-striped mb-0">
                                <thead>
                                    <tr">
                                        <th scope="col"> Nombre de la carrera </th>
                                        <th scope="col"> Cantidad de colaboradores </th>
                                    </tr>
                                </thead>

                                <tbody class="table-group-divider">
                                    <tr>
                                        <?php
                                        while($estadistica = $estadisticas_carreras->fetch_row()) {
                                            echo "<tr> ";
                                            echo "<th scope='row' class='py-3'> " . $estadistica[0] . " </th> ";
                                            echo "<td class='py-3'> " . $estadistica[1] .  " </td> ";
                                            echo " </tr>";
                                        }
                                        ?>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!--Estadísticas generales sobre las modalidades de los colaboradores--->
                    <div class="rounded-2 text-center fondo-pantone-azul-intermedio mx-auto mb-4"> 
                        <h6 class="text-center text-white fondo-pantone-azul-oscuro py-3 rounded-top mb-0 px-3"> 
                            Cantidad de colaboradores por modalidad
                        </h6>

                        <div class="table-responsive fondo-pantone-azul-intermedio rounded p-3">
                            <table class="table table-striped mb-0">
                                <thead>
                                    <tr">
                                        <th scope="col"> Nombre de la modalidad </th>
                                        <th scope="col"> Cantidad de colaboradores </th>
                                    </tr>
                                </thead>

                                <tbody class="table-group-divider">
                                    <tr>
                                        <?php
                                        while($estadistica = $estadisticas_modalidades->fetch_row()) {
                                            echo "<tr> ";
                                            echo "<th scope='row' class='py-3'> " . $estadistica[0] . " </th> ";
                                            echo "<td class='py-3'> " . $estadistica[1] .  " </td> ";
                                            echo " </tr>";
                                        }
                                        ?>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!--Estadísticas generales sobre los tipos de participación de los colaboradores--->
                    <div class="rounded-2 text-center fondo-pantone-azul-intermedio mx-auto mb-4"> 
                        <h6 class="text-center text-white fondo-pantone-azul-oscuro py-3 rounded-top mb-0 px-3"> 
                            Cantidad de colaboradores por tipo de participación
                        </h6>

                        <div class="table-responsive fondo-pantone-azul-intermedio rounded p-3 tabla-desglose">
                            <table class="table table-striped mb-0">
                                <thead>
                                    <tr">
                                        <th scope="col"> Nombre del tipo de participación </th>
                                        <th scope="col"> Cantidad de colaboradores </th>
                                    </tr>
                                </thead>

                                <tbody class="table-group-divider">
                                    <tr>
                                        <?php
                                        while($estadistica = $estadisticas_participaciones->fetch_row()) {
                                            echo "<tr> ";
                                            echo "<th scope='row' class='py-3'> " . $estadistica[0] . " </th> ";
                                            echo "<td class='py-3'> " . $estadistica[1] .  " </td> ";
                                            echo " </tr>";
                                        }
                                        ?>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!--Estadísticas generales sobre los turnos de los colaboradores--->
                    <div class="rounded-2 text-center fondo-pantone-azul-intermedio mx-auto"> 
                        <h6 class="text-center text-white fondo-pantone-azul-oscuro py-3 rounded-top mb-0 px-3"> 
                            Cantidad de colaboradores por turno
                        </h6>

                        <div class="table-responsive fondo-pantone-azul-intermedio rounded p-3 tabla-desglose">
                            <table class="table table-striped mb-0">
                                <thead>
                                    <tr">
                                        <th scope="col"> Nombre del turno </th>
                                        <th scope="col"> Cantidad de colaboradores </th>
                                    </tr>
                                </thead>

                                <tbody class="table-group-divider">
                                    <tr>
                                        <?php
                                        while($estadistica = $estadisticas_turnos->fetch_row()) {
                                            echo "<tr> ";
                                            echo "<th scope='row' class='py-3'> " . $estadistica[0] . " </th> ";
                                            echo "<td class='py-3'> " . $estadistica[1] .  " </td> ";
                                            echo " </tr>";
                                        }
                                        ?>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <?php
                    }
                    ?>

                    <?php
                        }
                    }
                    else {
                    ?>
                        <h4 class="text-center mt-4 mb-0">
                            <span class="badge bg-danger py-3">
                                No hay colaboradores a revisar
                            </span>
                        </h4>
                    <?php
                    }
                    # Cerrar la conexión con la base de datos.
                    $conexion_base->close();
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
        <script type="text/javascript">   
            document.body.onload = () => {
                document.querySelector("html").classList.remove("invisible");
            }

            document.getElementById("cierre-sesion").addEventListener("click", () => {
                document.getElementById("formulario").requestSubmit();
            });
            document.getElementById("formulario").addEventListener("submit", confirmarCierreSesion);

            dselect(document.getElementById("colaboradores"), { search: true, maxHeight: "200px" });
            dselect(document.getElementById("carreras"), { search: true, maxHeight: "200px" });
            dselect(document.getElementById("modalidades"), { search: false });
            dselect(document.getElementById("participaciones"), { search: false });
            dselect(document.getElementById("turnos"), { search: false });
        </script>
    </body>
</html>