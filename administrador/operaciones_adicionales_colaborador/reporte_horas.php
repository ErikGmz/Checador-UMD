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

    # Verificar si ya se realizó la 
    # estructuración del reporte de
    # cierto colaborador del sistema.
    if(isset($_GET["ID-colaborador"])) {
        if($usuario = $conexion_base->query("SELECT colaborador.ID, colaborador.nombres, colaborador.apellido_paterno, 
        colaborador.apellido_materno, carrera.nombre AS carrera, modalidad_colaborador.nombre AS modalidad, colaborador.numero_retardos,
        horario.hora_inicial, horario.hora_final, colaborador.numero_desbloqueos
        FROM colaborador JOIN carrera ON colaborador.ID_carrera = carrera.ID
        JOIN modalidad_colaborador ON colaborador.ID_modalidad = modalidad_colaborador.ID
        JOIN horario ON colaborador.ID_horario = horario.ID
        WHERE colaborador.ID = '" . @$_GET["ID-colaborador"] . "' LIMIT 1;")) {
            if($usuario->num_rows > 0) {
                # Definir los datos del usuario encontrado.
                $resultados = $usuario->fetch_row();
                $ID_colaborador = $resultados[0];
                $nombre_colaborador = $resultados[1] . " " . $resultados[2] . " " . $resultados[3];
                $carrera = $resultados[4];
                $modalidad = $resultados[5];
                $numero_retardos = $resultados[6];
                $numero_desbloqueos = $resultados[9];
                $hora_inicial = $resultados[7];
                $hora_final = $resultados[8];

                # Obtener todos los chequeos realizados por
                # el colaborador, respetando el rango de fechas.
                $chequeos = $conexion_base->query("SELECT chequeo.fecha_chequeo, chequeo.hora_inicial, 
                chequeo.hora_final, chequeo.tiempo_total, contingencia.tiempo_total AS tiempo_contingencia, 
                chequeo.bloqueo_registro FROM chequeo LEFT JOIN contingencia ON chequeo.fecha_chequeo = contingencia.fecha
                WHERE chequeo.ID_colaborador = '" . @$_GET["ID-colaborador"] . "';");

                # Obtener el conteo de horas totales 
                # de colaboración del usuario.
                if(isset($chequeos) && $chequeos->num_rows > 0) {
                    $calculo_horas_totales = $conexion_base->query("SELECT 
                    SEC_TO_TIME(SUM(TIME_TO_SEC(tiempo_total))) AS 
                    tiempo_colaboracion FROM chequeo WHERE ID_colaborador = '$ID_colaborador' 
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

                    # Obtener el tiempo total de contingencias
                    # del colaborador correspondiente.
                    $horas_contingencias = $conexion_base->query("SELECT SEC_TO_TIME(SUM(TIME_TO_SEC(tiempo_total))) 
                    AS tiempo_contingencias FROM contingencia WHERE ID_colaborador = '$ID_colaborador';");

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
        <link rel="stylesheet" href="../../css/clases_globales.css">
        <link rel="stylesheet" href="../../css/estilos_administradores.css">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.9.1/font/bootstrap-icons.css">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-gH2yIJqKdNHPEq0n4Mqa/HGKIhSkIHeL5AyhkYV8i59U5AR6csBvApHHNl/vI1Bx" crossorigin="anonymous">
        <link rel="stylesheet" href="../../css/dselect.min.css">
        
        <!--Título de la página-->
        <title> Reporte de horas </title>

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
                        Reporte de horas de colaborador del sistema
                    </h1>
                    <hr class="my-4 border border-1 border-dark">

                    <!--Formulario de selección del ID de un colaborador registrado-->
                    <?php
                    if(isset($colaboradores) && $colaboradores->num_rows > 0) {
                    ?>
                    <form method="GET" action="<?=$_SERVER['PHP_SELF']?>" class="mb-0 px-0 px-md-5">
                        <h5 class="text-center mb-3"> Selección del colaborador </h5>
                        <select class="form-select mb-4" name="ID-colaborador" id="colaboradores" required>
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

                        <div class="text-center">
                            <button class="btn btn-primary">
                                Estructurar reporte
                            </button>
                        </div>
                    </form>

                    <?php
                    if(isset($_GET["ID-colaborador"])) {
                    ?>
                        <div class="row px-1 mt-5">
                            <!--Datos de revisión-->
                            <div class="col-12 mb-4 mx-0">
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

                                            <p class="fw-semibold mb-2">
                                                Hora de salida: <?php echo date("h:i:s A", strtotime($hora_final)) ?>
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
                                                    "Viernes"];

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
                                                                    <th scope="col"> Tiempo total de contingencias </th>
                                                                    <th scope="col"> Tiempo total de bloqueos </th>
                                                                </tr>
                                                            </thead>

                                                            <tbody>
                                                                <tr>
                                                                    <td> <?php echo $horas_totales ?> </td>
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
                                    </div>
                                </div>
                            </div>  
                        </div>
                    <?php
                    }
                    }
                    else {
                    ?>
                        <h4 class="text-center mt-4">
                            <span class="badge bg-danger py-3">
                                No hay colaboradores a consultar
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
        <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"> </script>
        <script src="../../js/confirmar_operaciones.js"> </script>
        <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.5/dist/umd/popper.min.js" integrity="sha384-Xe+8cL9oJa6tN/veChSP7q+mnSPaj5Bcu9mPX5F5xIGE0DVittaqT5lorf0EI7Vk" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.min.js" integrity="sha384-ODmDIVzN+pFdexxHEHFBQH3/9/vQ9uori45z4JjnFsRydbmQbmL5t1tQ0culUzyK" crossorigin="anonymous"></script>
        <script src="../../js/dselect.min.js"> </script>
        <script type="text/javascript">
            // Mostrar el contenido una vez que la
            // página se cargue por completo.
            window.onload = () => {
                document.querySelector("html").classList.remove("d-none");
                setTimeout(() => {
                    document.querySelector("html").classList.remove("invisible");
                }, 20);
            }
            
            document.getElementById("cierre-sesion").addEventListener("click", () => {
                document.getElementById("formulario").requestSubmit();
            });
            document.getElementById("formulario").addEventListener("submit", confirmarCierreSesion);

            <?php
            if(isset($colaboradores) && $colaboradores->num_rows > 0) {
            ?>
                dselect(document.getElementById("colaboradores"), { search: true, maxHeight: "200px" });
            <?php
            }
            ?>
        </script>
    </body>
</html>