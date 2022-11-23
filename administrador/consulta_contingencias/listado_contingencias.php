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

    # Obtener todos los colaboradores del sistema.
    $colaboradores = $conexion_base->query("SELECT ID, CONCAT_WS(' ', nombres, apellido_paterno, apellido_materno) 
    AS nombre_completo FROM `colaborador`;");

    # Obtener todas las contingencias del sistema.
    $contingencias = $conexion_base->query("SELECT * FROM contingencia;");
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
        <title> Listado de contingencias </title>

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
                        Listado de contingencias del sistema
                    </h1>
                    <hr class="my-4 border border-1 border-dark">

                    <?php
                    if(isset($contingencias) && @$contingencias->num_rows > 0) {
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

                            <!--Selección de la fecha de contingencia-->
                            <div class="col-12 col-md-6 mb-4">
                                <label for="fecha-registro" class="form-label fw-semibold"> Fecha de contingencia </label>
                                <input type="date" name="fecha-registro" <?php if(isset($_GET["fecha-registro"])) echo "value='" . $_GET["fecha-registro"] . "'"?> min="2021-01-01"
                                class="form-control" id="fecha-registro" autocomplete="OFF">
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
                    if(isset($_GET["ID-colaborador"])) {
                        # Verificar si se aplicarán o no filtros al listado de colaboradores.
                        $aplicacion_filtros = $_GET["ID-colaborador"] != -1 || !empty($_GET["fecha-registro"]);

                        # Activar o no las estadísticas según la presencia de filtros en el listado.
                        if(!$aplicacion_filtros) {
                            # Obtener todas las contingencias desglosadas del sistema.
                            $contingencias_desglosadas = $conexion_base->query("SELECT * FROM desglose_contingencias;");

                            # Obtener el conteo de cuántas contingencias hay por colaborador.
                            $estadisticas_colaboradores = $conexion_base->query("SELECT * FROM cantidad_contingencias_colaboradores ORDER BY cantidad_contingencias;");

                            # Obtener el conteo de cuántas contingencias hay por fecha.
                            $estadisticas_fechas = $conexion_base->query("SELECT * FROM cantidad_contingencias_fechas ORDER BY cantidad_contingencias;");
                        }
                        else {
                            if($_GET["ID-colaborador"] != -1) {
                                $consulta = "SELECT * FROM desglose_contingencias WHERE ID = " . $_GET["ID-colaborador"];

                                if(!empty($_GET["fecha-registro"])) {
                                    $consulta .= " AND fecha = '" . $_GET["fecha-registro"] . "';";
                                }
                                else {
                                    $consulta .= " ORDER BY fecha;";
                                }

                                $contingencias_desglosadas = $conexion_base->query($consulta);
                            }
                            else {
                                $contingencias_desglosadas = $conexion_base->query("SELECT * FROM desglose_contingencias
                                WHERE fecha = '" . $_GET["fecha-registro"] . "' ORDER BY ID;");
                            }
                        }
                    ?>

                    <hr class="my-4 border border-1 border-dark">
                    <div class="col-12 text-center mb-4">
                        <h4 class="mb-0"> Resultados del listado especificado </h4>
                    </div>

                    <!--Listado general de las contingencias obtenidas--->
                    <div class="rounded-2 text-center fondo-pantone-azul-intermedio mx-auto"> 
                        <h6 class="text-center text-white fondo-pantone-azul-oscuro py-3 rounded-top mb-0 px-3"> 
                            Listado de contingencias
                        </h6>

                        <div class="table-responsive fondo-pantone-azul-intermedio rounded tabla-desglose p-3 <?php if(!$aplicacion_filtros) echo "mb-4" ?>">
                            <table class="table table-striped mb-0">
                                <thead>
                                    <tr">
                                        <th scope="col"> ID </th>
                                        <th scope="col"> Fecha de contingencia </th>
                                        <th scope="col"> Nombre completo </th>
                                        <th scope="col"> Hora inicial </th>
                                        <th scope="col"> Hora final </th>
                                        <th scope="col"> Tiempo total </th>
                                        <th scope="col"> Observaciones </th>
                                    </tr>
                                </thead>

                                <tbody class="table-group-divider">
                                    <tr>
                                        <?php
                                        if(isset($contingencias_desglosadas) && $contingencias_desglosadas->num_rows > 0) {
                                            while($contingencia = $contingencias_desglosadas->fetch_row()) {
                                                echo "<tr> ";
                                                echo "<th scope='row' class='py-3'> " . $contingencia[0] . " </th> ";
                                                echo "<th class='py-3'> " . date("d-m-Y", strtotime($contingencia[4])) .  " </th> ";
                                                echo "<td class='py-3'> " . $contingencia[1] .  " " . $contingencia[2] . " " . $contingencia[3] . " </td> ";
                                                echo "<td class='py-3'> " . date("h:i A", strtotime($contingencia[5])) . " </td> ";
                                                echo "<td class='py-3'> " . date("h:i A", strtotime($contingencia[6])) . " </td>";
                                                echo "<td class='py-3'> " . $contingencia[7] . " </td>";
                                                echo "<td class='py-3'> " . $contingencia[8] . " </td>";
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
                    <!--Estadísticas generales sobre los colaboradores de las contingencias--->
                    <div class="rounded-2 text-center fondo-pantone-azul-intermedio mx-auto mb-4"> 
                        <h6 class="text-center text-white fondo-pantone-azul-oscuro py-3 rounded-top mb-0 px-3"> 
                            Cantidad de contingencias por colaborador
                        </h6>

                        <div class="table-responsive fondo-pantone-azul-intermedio rounded tabla-desglose p-3">
                            <table class="table table-striped mb-0">
                                <thead>
                                    <tr">
                                        <th scope="col"> Nombre del colaborador </th>
                                        <th scope="col"> Cantidad de contingencias </th>
                                    </tr>
                                </thead>

                                <tbody class="table-group-divider">
                                    <tr>
                                        <?php
                                        if(isset($estadisticas_colaboradores) && $estadisticas_colaboradores->num_rows > 0) {
                                            while($estadistica = $estadisticas_colaboradores->fetch_row()) {
                                                echo "<tr> ";
                                                echo "<th scope='row' class='py-3'> " . $estadistica[0] . " </th> ";
                                                echo "<td class='py-3'> " . $estadistica[1] .  " </th> ";
                                                echo " </tr>";
                                            }
                                        }
                                        ?>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!--Estadísticas generales sobre las fechas de las contingencias--->
                    <div class="rounded-2 text-center fondo-pantone-azul-intermedio mx-auto"> 
                        <h6 class="text-center text-white fondo-pantone-azul-oscuro py-3 rounded-top mb-0 px-3"> 
                            Cantidad de contingencias por colaborador
                        </h6>

                        <div class="table-responsive fondo-pantone-azul-intermedio rounded tabla-desglose p-3">
                            <table class="table table-striped mb-0">
                                <thead>
                                    <tr">
                                        <th scope="col"> Fecha de contingencia </th>
                                        <th scope="col"> Cantidad de contingencias </th>
                                    </tr>
                                </thead>

                                <tbody class="table-group-divider">
                                    <tr>
                                        <?php
                                        if(isset($estadisticas_fechas) && $estadisticas_fechas->num_rows > 0) {
                                            while($estadistica = $estadisticas_fechas->fetch_row()) {
                                                echo "<tr> ";
                                                echo "<th scope='row' class='py-3'> " . $estadistica[0] . " </th> ";
                                                echo "<td class='py-3'> " . $estadistica[1] .  " </th> ";
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
                        }
                        }
                        }
                        else {
                            ?>
                                <p class="mb-0 fs-5 fw-semibold py-3 text-center"> 
                                    No hay contingencias registradas en el sistema
                                </p>
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
        </script>
    </body>
</html>