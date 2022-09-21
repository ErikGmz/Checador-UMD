<?php
    # Iniciar y verificar la conexión
    # con la base de datos.
    $conexion_base = new mysqli("localhost", "root", "", "checadorumd");
    if($conexion_base->connect_error) {
        die("Hubo un error al conectar con la base de datos. " . $conexion_base->connect_error);
    }

    # Definir la zona horaria.
    date_default_timezone_set('America/Mexico_City');

    include("../funciones_adicionales/formateado_numero.php");
    if(!isset($_GET["ID-colaborador"])) {
        $_GET["ID-colaborador"] = "''";
    }

    # Verificar si el usuario especificado
    # existe en la base de datos.
    if($usuario = $conexion_base->query("SELECT colaborador.ID, colaborador.nombres, colaborador.apellido_paterno, 
    colaborador.apellido_materno, carrera.nombre AS carrera, modalidad_colaborador.nombre AS modalidad
    FROM colaborador JOIN carrera ON colaborador.ID_carrera = carrera.ID
    JOIN modalidad_colaborador ON colaborador.ID_modalidad = modalidad_colaborador.ID
    WHERE colaborador.ID = " . @$_GET["ID-colaborador"] . " LIMIT 1;")) {
        if($usuario->num_rows > 0) {
            # Definir los datos del usuario encontrado.
            $resultados = $usuario->fetch_row();
            $ID_colaborador = $resultados[0];
            $nombre_colaborador = $resultados[1] . " " . $resultados[2] . " " . $resultados[3];
            $carrera = $resultados[4];
            $modalidad = $resultados[5];

            # Comprobar si se indicaron horas de becario.
            if(isset($_GET["horas-becario"]) && is_numeric(@$_GET["horas-becario"])) {
                $horas_becario = $_GET["horas-becario"];
            }
            else {
                $horas_becario = 0;
            }

            # Comprobar si la fecha inicial es válida.
            if(strtotime(@$_GET["fecha-inicial"]) >= strtotime("2021-01-01") &&
            strtotime(@$_GET["fecha-inicial"]) <= strtotime("2030-12-30")) {
                $fecha_inicial = @$_GET["fecha-inicial"];
            }
            else {
                $fecha_inicial = "2021-01-01";
            }

            # Comprobar si la fecha final es válida.
            if(strtotime(@$_GET["fecha-final"]) <= strtotime("2030-12-31") && 
            strtotime(@$_GET["fecha-final"]) >= strtotime("2021-01-02")) {
                $fecha_final = @$_GET["fecha-final"];
            }
            else {
                $fecha_final = "2030-12-31";
            }

            # Verificar si el rango de fechas es válido;
            # en caso contrario, entonces será corregido.
            if(strtotime($fecha_final) < strtotime($fecha_inicial)) {
                $auxiliar = $fecha_final;
                $fecha_final = $fecha_inicial;
                $fecha_inicial = $auxiliar;
            }

            # Obtener todos los chequeos realizados por
            # el colaborador, respetando el rango de fechas.
            $chequeos = $conexion_base->query("SELECT chequeo.fecha_chequeo, chequeo.hora_inicial, 
            chequeo.hora_final, chequeo.tiempo_total, contingencia.tiempo_total AS tiempo_contingencia, 
            chequeo.desbloqueo FROM chequeo LEFT JOIN contingencia ON chequeo.fecha_chequeo = contingencia.fecha
            WHERE chequeo.ID_colaborador = " . @$_GET["ID-colaborador"] . "
            AND chequeo.fecha_chequeo BETWEEN '$fecha_inicial' AND '$fecha_final';");

            # Obtener el conteo de horas totales 
            # de colaboración del usuario.
            if(isset($chequeos) && $chequeos->num_rows > 0) {
                $calculo_horas_totales = $conexion_base->query("SELECT 
                SEC_TO_TIME(SUM(TIME_TO_SEC(tiempo_total))) AS 
                tiempo_colaboracion FROM chequeo WHERE ID_colaborador = $ID_colaborador
                AND fecha_chequeo BETWEEN '$fecha_inicial' AND '$fecha_final';");
                
                if(isset($calculo_horas_totales) && $calculo_horas_totales->num_rows > 0) {
                    $resultado = $calculo_horas_totales->fetch_row();
                    $horas_totales = $resultado[0];
                    $calculo_horas_totales->close();
                }
                else {
                    $horas_totales = "N/A";
                }

                # Obtener las horas correspondientes
                # al servicio social, para el caso de
                # los colaboradores que son becarios.
                list($horas, $minutos, $segundos) = explode(":", $horas_totales);
                $horas = (int)$horas;
                $horas -= $horas_becario;
                if($horas < 0) {
                    $horas_servicio = "N/A";
                }
                else {
                    $horas = completar_cero($horas);
                    $horas_servicio = $horas . ":" . $minutos . ":" . $segundos;
                }

                # Obtener el tiempo total de contingencias
                # del colaborador correspondiente.
                $horas_contingencias = $conexion_base->query("SELECT SEC_TO_TIME(SUM(TIME_TO_SEC(tiempo_total))) 
                AS tiempo_contingencias FROM contingencia WHERE ID_colaborador = $ID_colaborador
                AND fecha BETWEEN '$fecha_inicial' AND '$fecha_final';");

                if(isset($horas_contingencias) && $horas_contingencias->num_rows > 0) {
                    $resultado = $horas_contingencias->fetch_row();
                    if(!empty($resultado[0])) {
                        $tiempo_contingencias = $resultado[0];
                    }
                    else {
                        $tiempo_contingencias = "N/A";
                    }
                    $horas_contingencias->close();
                }
                else {
                    $tiempo_contingencias = "N/A";
                }

                # Obtener la cantidad de desbloqueos
                # del colaborador correspondiente.
                $cantidad_desbloqueos = $conexion_base->query("SELECT SUM(desbloqueo) FROM chequeo 
                WHERE ID_colaborador = $ID_colaborador AND fecha_chequeo BETWEEN '$fecha_inicial' AND '$fecha_final';");

                if(isset($cantidad_desbloqueos) && $cantidad_desbloqueos->num_rows > 0) {
                    $resultado = $cantidad_desbloqueos->fetch_row();
                    if(!empty($resultado[0]) && $resultado[0] > 0) {
                        $veces_desbloqueos = $resultado[0];
                    }
                    else {
                        $veces_desbloqueos = "N/A";
                    }
                    $cantidad_desbloqueos->close();
                }
                else {
                    $veces_desbloqueos = "N/A";
                }
            }
        } 
        $usuario->close();
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
        <link rel="stylesheet" href="../css/estilos_revision_horas.css">
        <link rel="stylesheet" href="../css/estilos_usuarios.css">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.9.1/font/bootstrap-icons.css">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-gH2yIJqKdNHPEq0n4Mqa/HGKIhSkIHeL5AyhkYV8i59U5AR6csBvApHHNl/vI1Bx" crossorigin="anonymous">

        <!--Título de la página-->
        <title> Listado de horas de colaboración </title>

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
        <div class="container-xxl min-vh-100 p-4 py-lg-5">
            <div class="row">
                <!--Logotipos y botones de la página-->
                <?php
                    include("../componentes_interfaz/logotipos_superiores_pagina.php");
                ?>

                <!--Recuadro para los datos de la revisión-->
                <div class="col-12 centrado-flex">
                    <div class="rounded-4 p-4 fondo-pantone-azul-claro contenedor">
                        <!--Título-->
                        <h4 class="text-center"> Revisión desglosada de horas </h4>
                        <hr class="border border-1 border-dark mb-4"> 

                        <div class="row px-1">
                            <!--Datos de revisión-->
                            <div class="col-12 mb-4">
                                <div class="rounded-2 fondo-pantone-verde-claro recuadro mx-auto"> 
                                    <h6 class="text-center text-white fondo-pantone-verde-oscuro py-3 rounded-top mb-0 px-3"> 
                                        Datos de revisión
                                    </h6>

                                    <div class="p-4 text-center">
                                        <?php
                                        if(!isset($resultados)) {
                                        ?>
                                            <p class="fw-semibold mb-0">
                                                El usuario no fue encontrado
                                            </p>
                                        <?php
                                        }
                                        else {
                                        ?>
                                            <p class="fw-semibold mb-2">
                                                Nombre: <?php echo $nombre_colaborador ?>
                                            </p>

                                            <p class="fw-semibold mb-2">
                                                Carrera: <?php echo $carrera ?>
                                            </p>

                                            <p class="fw-semibold mb-4">
                                                ID: <?php echo $ID_colaborador ?>
                                            </p>

                                            <p class="fw-semibold mb-2">
                                                Modalidad: <?php echo $modalidad ?>
                                            </p>

                                            <p class="fw-semibold mb-2">
                                                Horas de becario: <?php echo $horas_becario ?>
                                            </p>

                                            <p class="fw-semibold mb-2">
                                                Fecha inicial: <?php echo date("d-m-Y", strtotime($fecha_inicial)); ?>
                                            </p>

                                            <p class="fw-semibold mb-0">
                                                Fecha final: <?php echo date("d-m-Y", strtotime($fecha_final)); ?>
                                            </p>
                                        <?php
                                        }
                                        ?>
                                    </div>
                                </div>
                            </div>

                            <!--Datos de revisión-->
                            <div class="col-12">
                                <div class="rounded-2 text-center fondo-pantone-azul-intermedio mx-auto"> 
                                    <h6 class="text-center text-white fondo-pantone-azul-oscuro py-3 rounded-top mb-0 px-3"> 
                                        Revisión de horas desglosadas
                                    </h6>

                                    <div class="table-responsive mx-auto py-4 px-0 px-sm-4"> 
                                        <table class="table table-striped mb-0">
                                            <thead>
                                                <tr>
                                                    <th scope="col"> Fecha de registro </th>
                                                    <th scope="col"> Contingencia </th>
                                                    <th scope="col"> Desbloqueo </th>
                                                    <th scope="col"> Día de registro </th>
                                                    <th scope="col"> Hora de entrada </th>
                                                    <th scope="col"> Hora de salida </th>
                                                    <th scope="col"> Tiempo total </th>
                                                </tr>
                                            </thead>

                                            <tbody class="table-group-divider">
                                                <?php
                                                if(isset($chequeos) && $chequeos->num_rows > 0) {
                                                ?>
                                                <tr>
                                                    <?php
                                                    $dias = ["Lunes", "Martes", "Miércoles", "Jueves",
                                                    "Viernes"];

                                                    while($chequeo = $chequeos->fetch_row()) {
                                                        echo "<tr> ";
                                                        echo "<th scope='row'> " . date("d-m-Y", strtotime($chequeo[0])) . " </th> ";
                                                        echo "<td> " . ((empty($chequeo[4])) ? "N/A" : $chequeo[4]) .  " </td> ";
                                                        echo "<td> " . (($chequeo[5] == "0") ? "N/A" : $chequeo[5]) .  " </td> ";
                                                        echo "<td> " . $dias[date("w", strtotime($chequeo[0])) - 1] . " </td> ";
                                                        echo "<td> " . date("h:i:s A", strtotime($chequeo[1])) . " </td> ";
                                                        echo "<td> " . ((empty($chequeo[2])) ? "N/A" : date("h:i:s A", strtotime($chequeo[2]))) . " </td>";
                                                        echo "<td> " . ((empty($chequeo[3])) ? "N/A" : $chequeo[3]) . " </td>";
                                                        echo " </tr>";
                                                    }
                                                    ?>
                                                </tr>
                                                <?php
                                                }
                                                ?>
                                            </tbody>

                                            <?php
                                            if(isset($chequeos) && $chequeos->num_rows > 0) {
                                            ?>
                                            <tfoot class="table-group-divider">
                                                <tr>
                                                    <td colspan="7" class="px-0">
                                                        <table class="table table-borderless mb-0">
                                                            <thead>
                                                                <th scope="col"> Horas totales </th>
                                                                <th scope="col"> Horas de servicio </th>
                                                                <th scope="col"> Tiempo total de contingencias </th>
                                                                <th scope="col"> Cantidad de desbloqueos </th>
                                                            </thead>

                                                            <tbody>
                                                                <td> <?php echo $horas_totales ?> </td>
                                                                <td> <?php echo $horas_servicio ?> </td>
                                                                <td> <?php echo $tiempo_contingencias ?> </td>
                                                                <td> <?php echo $veces_desbloqueos ?> </td>
                                                            </tbody>
                                                        </table>
                                                    </td>
                                                </tr>
                                            </tfoot>
                                            <?php
                                            }
                                            if(isset($chequeos)) {
                                                $chequeos->close();
                                            }
                                            ?>
                                        </table>
                                    </div>
                                </div>
                            </div>  
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <?php
            # Cerrar la conexión con la base de datos.
            $conexion_base->close();
        ?>

        <!--Scripts de la página-->
        <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.5/dist/umd/popper.min.js" integrity="sha384-Xe+8cL9oJa6tN/veChSP7q+mnSPaj5Bcu9mPX5F5xIGE0DVittaqT5lorf0EI7Vk" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.min.js" integrity="sha384-ODmDIVzN+pFdexxHEHFBQH3/9/vQ9uori45z4JjnFsRydbmQbmL5t1tQ0culUzyK" crossorigin="anonymous"></script>
    </body>
</html>