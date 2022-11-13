<?php
    session_start();

    # Verificar si algún administrador ya 
    # inició su correspondiente sesión.
    if(isset($_SESSION["ID_administrador"])) {
        header("location: ../administrador/menu_principal/menu_administrador.php");
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

    # Definir la zona horaria.
    date_default_timezone_set('America/Mexico_City');

    include("../funciones_adicionales/formateado_numero.php");
    if(!isset($_GET["ID-colaborador"])) {
        $_GET["ID-colaborador"] = "''";
    }

    # Verificar si el usuario especificado
    # existe en la base de datos.
    if($usuario = $conexion_base->query("SELECT * FROM desglose_colaboradores
    WHERE ID = '" . @$_GET["ID-colaborador"] . "' LIMIT 1;")) {
        if($usuario->num_rows > 0) {
            # Definir los datos del usuario encontrado.
            $resultados = $usuario->fetch_row();
            $ID_colaborador = $resultados[0];
            $nombre_colaborador = $resultados[1];
            $fecha_nacimiento = $resultados[2];
            $numero_retardos = $resultados[3];
            $numero_desbloqueos = $resultados[4];
            $carrera = $resultados[5];
            $modalidad = $resultados[6];
            $hora_inicial = $resultados[7];
            $hora_final = $resultados[8];
            
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
            $chequeos = $conexion_base->query("SELECT * FROM desglose_chequeos WHERE ID_colaborador = '" . @$_GET["ID-colaborador"] . "'
            AND fecha_chequeo BETWEEN '$fecha_inicial' AND '$fecha_final' ORDER BY fecha_chequeo ASC;");

            # Obtener el conteo de horas totales 
            # de colaboración del usuario.
            if(isset($chequeos) && $chequeos->num_rows > 0) {
                $calculo_horas_totales = $conexion_base->query("SELECT 
                SEC_TO_TIME(SUM(TIME_TO_SEC(tiempo_total))) AS 
                tiempo_colaboracion FROM chequeo WHERE ID_colaborador = '$ID_colaborador'
                AND fecha_chequeo BETWEEN '$fecha_inicial' AND '$fecha_final'
                AND bloqueo_registro = '0';");
                
                if(isset($calculo_horas_totales) && $calculo_horas_totales->num_rows > 0) {
                    $resultado = $calculo_horas_totales->fetch_row();
                    if(empty($resultado[0])) {
                        $horas_totales = "N/A";
                    }
                    else {
                        $horas_totales = $resultado[0];
                    }
                }
                else {
                    $horas_totales = "N/A";
                }
                @$calculo_horas_totales->close();

                # Obtener las horas correspondientes
                # al servicio social, para el caso de
                # los colaboradores que son becarios.
                if($horas_totales != "N/A") {
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
                }
                else {
                    $horas_servicio = "N/A";
                }

                # Obtener el tiempo total de contingencias
                # del colaborador correspondiente.
                $horas_contingencias = $conexion_base->query("SELECT SEC_TO_TIME(SUM(TIME_TO_SEC(tiempo_total))) 
                AS tiempo_contingencias FROM contingencia WHERE ID_colaborador = '$ID_colaborador'
                AND fecha BETWEEN '$fecha_inicial' AND '$fecha_final';");

                if(isset($horas_contingencias) && $horas_contingencias->num_rows > 0) {
                    $resultado = $horas_contingencias->fetch_row();
                    if(!empty($resultado[0])) {
                        $tiempo_contingencias = $resultado[0];
                    }
                    else {
                        $tiempo_contingencias = "N/A";
                    }
                }
                else {
                    $tiempo_contingencias = "N/A";
                }
                @$horas_contingencias->close();

                # Obtener la cantidad de horas bloqueadas
                # del colaborador correspondiente.
                $resultado_bloqueo = $conexion_base->query("SELECT SEC_TO_TIME(SUM(TIME_TO_SEC(tiempo_total))) 
                AS tiempo_bloqueo FROM chequeo WHERE ID_colaborador = '$ID_colaborador'
                AND fecha_chequeo BETWEEN '$fecha_inicial' AND '$fecha_final'
                AND bloqueo_registro = '1';");

                if(isset($resultado_bloqueo) && $resultado_bloqueo->num_rows > 0) {
                    $resultado = $resultado_bloqueo->fetch_row();
                    if(!empty($resultado[0])) {
                        $tiempo_bloqueo = $resultado[0];
                    }
                    else {
                        $tiempo_bloqueo = "N/A";
                    }
                }
                else {
                    $tiempo_bloqueo = "N/A";
                }
                @$resultado_bloqueo->close();
            }
        } 
        $usuario->close();
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
        <link rel="stylesheet" href="../css/clases_globales.css">
        <link rel="stylesheet" href="../css/estilos_revision_horas.css">
        <link rel="stylesheet" href="../css/estilos_usuarios.css">
        <link rel="stylesheet" href="../css/bootstrap/bootstrap-icons.css">
        <link rel="stylesheet" href="../css/bootstrap/bootstrap.min.css">

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
                        <h4 class="text-center"> Revisión de horas </h4>
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
                                                El colaborador no fue encontrado
                                            </p>
                                        <?php
                                        }
                                        else {
                                        ?>
                                            <p class="fw-semibold mb-2">
                                                Nombre: <?php echo $nombre_colaborador ?>
                                            </p>

                                            <?php
                                            if(isset($fecha_nacimiento)) {
                                            ?>
                                            <p class="fw-semibold mb-2">
                                                Fecha de nacimiento: <?php echo date("d-m-Y", strtotime($fecha_nacimiento)) ?>
                                            </p>
                                            <?php
                                            }
                                            ?>

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
                                                Número de retardos: <?php echo $numero_retardos ?>
                                            </p>

                                            <p class="fw-semibold mb-2">
                                                Número de desbloqueos: <?php echo $numero_desbloqueos ?>
                                            </p>

                                            <p class="fw-semibold mb-2">
                                                Hora de entrada: <?php echo date("h:i:s A", strtotime($hora_inicial)) ?>
                                            </p>

                                            <p class="fw-semibold mb-4">
                                                Hora de salida: <?php echo date("h:i:s A", strtotime($hora_final)) ?>
                                            </p>

                                            <p class="fw-semibold mb-2">
                                                Horas de becario: <?php echo $horas_becario ?>
                                            </p>

                                            <p class="fw-semibold mb-2">
                                                Fecha inicial de revisión: <?php echo date("d-m-Y", strtotime($fecha_inicial)); ?>
                                            </p>

                                            <p class="fw-semibold mb-2">
                                                Fecha final de revisión: <?php echo date("d-m-Y", strtotime($fecha_final)); ?>
                                            </p>

                                            <p class="fw-semibold mb-0">
                                                Tipo de revisión: <?php echo ((@$_GET["chequeo"] == "desglose") ? "Desglosada" : "Resumida") ?>
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
                                        Reporte de horas
                                    </h6>

                                    <div class="table-responsive mx-auto py-4 px-0 px-sm-4"> 
                                        <?php
                                        if(@$_GET["chequeo"] == "desglose") {
                                        ?>
                                        <table class="table table-striped mb-0">
                                            <thead>
                                                <tr">
                                                    <th scope="col"> Fecha de registro </th>
                                                    <th scope="col"> Contingencia </th>
                                                    <th scope="col"> Bloqueo </th>
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
                                                    "Viernes", "Sábado", "Domingo"];

                                                    while($chequeo = $chequeos->fetch_row()) {
                                                        echo "<tr> ";
                                                        echo "<th scope='row' class='py-3'> " . date("d-m-Y", strtotime($chequeo[0])) . " </th> ";
                                                        echo "<td class='py-3'> " . ((empty($chequeo[4])) ? "N/A" : $chequeo[4]) .  " </td> ";
                                                        echo "<td class='py-3'> " . (($chequeo[5] == "0") ? "N/A" : $chequeo[5]) .  " </td> ";
                                                        echo "<td class='py-3'> " . $dias[date("w", strtotime($chequeo[0])) - 1] . " </td> ";
                                                        echo "<td class='py-3'> " . date("h:i:s A", strtotime($chequeo[1])) . " </td> ";
                                                        echo "<td class='py-3'> " . ((empty($chequeo[2])) ? "N/A" : date("h:i:s A", strtotime($chequeo[2]))) . " </td>";
                                                        echo "<td class='py-3'> " . ((empty($chequeo[3])) ? "N/A" : $chequeo[3]) . " </td>";
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
                                            <tfoot class="table-group-divider remarcado-inferior">
                                                <tr>
                                                    <td colspan="7" class="px-0 py-3">
                                                        <table class="table table-borderless mb-0">
                                                            <thead>
                                                                <tr>
                                                                    <th scope="col"> Horas totales </th>
                                                                    <th scope="col"> Horas de servicio </th>
                                                                    <th scope="col"> Tiempo total de contingencias </th>
                                                                    <th scope="col"> Tiempo total de bloqueos </th>
                                                                </tr>
                                                            </thead>

                                                            <tbody>
                                                                <tr>
                                                                    <td> <?php echo $horas_totales ?> </td>
                                                                    <td> <?php echo $horas_servicio ?> </td>
                                                                    <td> <?php echo $tiempo_contingencias ?> </td>
                                                                    <td> <?php echo $tiempo_bloqueo ?> </td>
                                                                </tr>
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
                                        <?php
                                        }
                                        else {
                                        ?>
                                        <table class="table table-striped mb-0">
                                            <thead>
                                                <tr>
                                                    <th scope="col"> Horas totales </th>
                                                    <th scope="col"> Horas de servicio </th>
                                                    <th scope="col"> Tiempo total de contingencias </th>
                                                    <th scope="col"> Tiempo total de bloqueos </th>
                                                </tr>
                                            </thead>

                                            <tbody class="table-group-divider">
                                                <?php
                                                if(isset($chequeos) && $chequeos->num_rows > 0) {
                                                ?>
                                                <tr>
                                                    <td class="py-3"> <?php echo $horas_totales ?> </td>
                                                    <td class="py-3"> <?php echo $horas_servicio ?> </td>
                                                    <td class="py-3"> <?php echo $tiempo_contingencias ?> </td>
                                                    <td class="py-3"> <?php echo $tiempo_bloqueo ?> </td>
                                                </tr>
                                                <?php
                                                }
                                                ?>
                                            </tbody>
                                        </table>
                                        <?php
                                        }
                                        ?>
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
        <script src="../js/bootstrap/jquery-3.6.0.min.js"> </script>
        <script src="../js/bootstrap/bootstrap.bundle.min.js"> </script>
        <script type="text/javascript">
            document.body.onload = () => {
                document.querySelector("html").classList.remove("invisible");
            }
        </script>
    </body>
</html>