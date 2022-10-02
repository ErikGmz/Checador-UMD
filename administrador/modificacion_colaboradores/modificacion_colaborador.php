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

    # Obtener todas las carreras registradas.
    $carreras = $conexion_base->query("SELECT * FROM carrera;");

    # Obtener todas las modalidades registradas.
    $modalidades = $conexion_base->query("SELECT * FROM modalidad_colaborador;");

    # Obtener turnos registrados.
    $turnos = $conexion_base->query("SELECT * FROM turno;");
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
        <title> Modificación de colaborador </title>

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
                        Modificación de colaborador del sistema
                    </h1>
                    <hr class="my-4 border border-1 border-dark">

                    <!--Formulario de selección del ID de un colaborador registrado-->
                    <?php
                    if(isset($colaboradores) && $colaboradores->num_rows > 0) {
                    ?>
                    <form method="GET" action="<?=$_SERVER['PHP_SELF']?>" class="mb-0 px-0 px-md-5">
                        <h5 class="text-center mb-3"> Selección del colaborador a modificar </h5>
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
                                Cargar datos
                            </button>
                        </div>
                    </form>

                    <?php
                    if(isset($_GET["ID-colaborador"])) {
                        # Buscar al colaborador introducido 
                        # en la lista de búsqueda.
                        if($usuario = $conexion_base->query("SELECT IF(SUBSTRING(nombres, 1, LOCATE(' ', nombres)) = '',
                        TRIM(SUBSTRING(nombres, LOCATE(' ', nombres) + 1)), 
                        TRIM(SUBSTRING(nombres, 1, LOCATE(' ', nombres)))) AS primer_nombre,
                        IF(SUBSTRING(nombres, 1, LOCATE(' ', nombres)) != '',
                        TRIM(SUBSTRING(nombres, LOCATE(' ', nombres) + 1)), '') AS segundo_nombre,
                        colaborador.apellido_paterno, colaborador.apellido_materno,
                        colaborador.ID_carrera, colaborador.ID_modalidad, 
                        TIME_FORMAT(horario.hora_inicial, '%H:%i') AS hora_inicial,
                        TIME_FORMAT(horario.hora_final, '%H:%i') AS hora_final
                        FROM colaborador JOIN horario ON colaborador.ID_horario = horario.ID
                        WHERE colaborador.ID = '" . $_GET["ID-colaborador"] . "';")) {
                            if($usuario->num_rows > 0) {
                                $datos_usuario = $usuario->fetch_row();
                                $valido = true;
                                ?>
                                <hr class="my-4 border border-1 border-dark">
                                <?php
                    ?>
                    <form method="POST" action="procesar_modificacion_colaborador.php" 
                    class="mb-0" id="modificacion-colaborador">
                        <div class="row mb-2">
                            <!--Datos nuevos del colaborador-->
                            <div class="col-12 text-center mb-4">
                                <h4 class="mb-0"> Datos nuevos del colaborador </h4>
                            </div>

                            <div class="col-12 mb-4 texto-colaborador">
                                <label for="ID-colaborador" class="form-label fw-semibold"> ID del colaborador (*) </label>
                                <input type="text" class="form-control recuadro-ID" id="ID-colaborador" 
                                onchange="verificarColaborador(document.getElementById('ID-colaborador').value, 'ID-colaborador', 2)" 
                                autocomplete="OFF" autofocus="ON" required name="ID-colaborador" pattern="[0-9]{1,10}"
                                value="<?=$_GET['ID-colaborador']?>">
                                <div class="form-text"> Campo obligatorio. Solo se admite un número de máximo 10 dígitos. </div>
                            </div>

                            <div class="col-12 col-md-6 mb-4">
                                <label for="primer-nombre" class="form-label fw-semibold"> Primer nombre (*) </label>
                                <input type="text" class="form-control mayusculas-iniciales" id="primer-nombre" 
                                autocomplete="OFF" required name="primer-nombre" pattern="^[a-zA-ZÁÉÍÓÚáéíóúñÑ]{1,}$"
                                value="<?=$datos_usuario[0]?>">
                                <div class="form-text"> Campo obligatorio. Solo se puede introducir una palabra (sin espacios). </div>
                            </div>

                            <div class="col-12 col-md-6 mb-4">
                                <label for="segundo-nombre" class="form-label fw-semibold"> Segundo nombre </label>
                                <input type="text" class="form-control mayusculas-iniciales" id="segundo-nombre" 
                                autocomplete="OFF" name="segundo-nombre" pattern="^[a-zA-ZÁÉÍÓÚáéíóúñÑ ]{1,}$"
                                value="<?=$datos_usuario[1]?>">
                                <div class="form-text"> Campo opcional. Se admiten nombres adicionales. </div>
                            </div>

                            <div class="col-12 col-md-6 mb-4 mb-md-0">
                                <label for="primer-apellido" class="form-label fw-semibold"> Apellido paterno (*) </label>
                                <input type="text" class="form-control mayusculas-iniciales" id="primer-apellido" 
                                autocomplete="OFF" required name="primer-apellido" pattern="^[a-zA-ZÁÉÍÓÚáéíóúñÑ ]{1,}$"
                                value="<?=$datos_usuario[2]?>">
                                <div class="form-text"> Campo obligatorio. Se admiten palabras adicionales</div>
                            </div>

                            <div class="col-12 col-md-6">
                                <label for="segundo-apellido" class="form-label fw-semibold"> Apellido materno </label>
                                <input type="text" class="form-control mayusculas-iniciales" id="segundo-apellido" 
                                autocomplete="OFF" name="segundo-apellido" pattern="^[a-zA-ZÁÉÍÓÚáéíóúñÑ ]{1,}$"
                                value="<?=$datos_usuario[3]?>">
                                <div class="form-text"> Campo opcional. Se admiten apellidos adicionales. </div>
                            </div>

                            <div class="col-12 my-4">
                                <hr class="mb-0 border border-1 border-dark">
                            </div>

                            <!--Datos sobre la participación en el sistema-->
                            <div class="col-12 text-center mb-4">
                                <h4 class="mb-0"> Participación en el sistema </h4>
                            </div>

                            <div class="col-12 col-md-6 mb-4">
                                <label for="carreras" class="form-label fw-semibold"> Carrera (*) </label>
                                <select class="form-select" name="carrera" id="carreras" required>
                                    <?php
                                        if(isset($carreras) && $carreras->num_rows > 0) {
                                            while($carrera = $carreras->fetch_row()) {
                                                if($carrera[0] == $datos_usuario[4]) {
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
                                    Campo obligatorio. Si no se selecciona ninguna opción, entonces 
                                    el sistema escogerá por defecto la primera carrera de la lista.
                                </div>
                            </div>

                            <div class="col-12 col-md-6 mb-4">
                                <label for="modalidades" class="form-label fw-semibold"> Modalidad (*) </label>
                                <select class="form-select" name="modalidad" id="modalidades">
                                    <?php
                                        if(isset($modalidades) && $modalidades->num_rows > 0) {
                                            while($modalidad = $modalidades->fetch_row()) {
                                                if($modalidad[0] == $datos_usuario[5]) {
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
                                    Campo obligatorio.
                                </div>
                            </div>

                            <div class="col-12 col-md-6 mb-4 mb-md-0">
                                <label for="hora-entrada" class="form-label fw-semibold"> Hora de entrada (*) </label>
                                <input type="text" class="form-control mayusculas-iniciales" id="hora-entrada" 
                                autocomplete="OFF" required name="hora-entrada" placeholder="08:00"
                                pattern="^((0[8-9]|1[0-9]|2[0]):[0-5][0-9])|21:00$"
                                value="<?=$datos_usuario[6]?>"
                                oninput="verificarRangosHoras('hora-entrada', 'hora-salida')">
                                <div class="form-text"> 
                                    Formato de 08:00 a 21:00 horas.
                                </div>
                            </div>

                            <div class="col-12 col-md-6">
                                <label for="hora-salida" class="form-label fw-semibold"> Hora de salida (*) </label>
                                <input type="text" class="form-control mayusculas-iniciales" id="hora-salida" 
                                autocomplete="OFF" required name="hora-salida" placeholder="12:00"
                                pattern="^((0[8-9]|1[0-9]|2[0]):[0-5][0-9])|21:00$"
                                value="<?=$datos_usuario[7]?>"
                                oninput="verificarRangosHoras('hora-entrada', 'hora-salida')">
                                <div class="form-text"> 
                                    Formato de 08:00 a 21:00 horas.
                                </div>
                            </div>

                            <div class="col-12 my-4">
                                <hr class="mb-0 border border-1 border-dark">
                            </div>
                        </div>
                        <input type="hidden" id="anterior-ID-colaborador" name="anterior-ID-colaborador" value="<?=$_GET["ID-colaborador"]?>">

                        <!--Botón de registro de colaborador-->
                        <div class="text-center">
                            <button class="btn btn-primary">
                                Modificar colaborador
                            </button>
                        </div>
                    </form>
                    <?php
                                }
                                else {
                                    ?>
                                        <hr class="my-4 border border-1 border-dark">
                                        <h4 class="text-center mt-4">
                                            <span class="badge bg-danger py-3">
                                                El colaborador no fue encontrado
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
                                            El colaborador no fue encontrado
                                        </span>
                                    </h4>
                                <?php
                            }
                            @$usuario->close();
                        }
                    }
                    else {
                    ?>
                        <h4 class="text-center mt-4">
                            <span class="badge bg-danger py-3">
                                No hay colaboradores a modificar
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
        <script src="../../js/peticiones_ajax/verificar_colaborador.js"> </script>
        <script src="../../js/verificar_rangos_horas.js"> </script>
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
                if(isset($_GET["ID-colaborador"], $valido) && @$valido) {
                ?>
                    document.getElementById("modificacion-colaborador").addEventListener("submit", confirmarModificacionColaborador);
                    dselect(document.getElementById("carreras"), { search: true, maxHeight: "200px" });
                    dselect(document.getElementById("modalidades"), { search: false });
                <?php
                }
            }
            ?>
        </script>
    </body>
</html>