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

    # Obtener todas las carreras registradas.
    $carreras = $conexion_base->query("SELECT * FROM carrera;");

    # Obtener todas las modalidades registradas.
    $modalidades = $conexion_base->query("SELECT * FROM modalidad_colaborador;");

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
        <link rel="stylesheet" href="../../css/desplazamiento_instantaneo.css">
        <link rel="stylesheet" href="../../css/clases_globales.css">
        <link rel="stylesheet" href="../../css/estilos_administradores.css">
        <link rel="stylesheet" href="../../css/bootstrap/bootstrap-icons.css">
        <link rel="stylesheet" href="../../css/bootstrap/bootstrap.min.css">
        <link rel="stylesheet" href="../../css/dselect.min.css">
        
        <!--Título de la página-->
        <title> Adición de colaborador </title>

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
                        Adición de colaborador al sistema
                    </h1>
                    <hr class="my-4 border border-1 border-dark">

                    <form method="POST" action="procesar_adicion_colaborador.php" 
                    class="mb-0" id="registro-colaborador">
                        <div class="row mb-2">
                            <!--Datos iniciales del colaborador-->
                            <div class="col-12 text-center mb-4">
                                <h4 class="mb-0"> Datos iniciales del colaborador </h4>
                            </div>

                            <div class="col-12 mb-4 texto-colaborador">
                                <label for="ID-colaborador" class="form-label fw-semibold"> ID del colaborador (*) </label>
                                <input type="text" class="form-control recuadro-ID" id="ID-colaborador" 
                                onchange="verificarColaborador(document.getElementById('ID-colaborador').value, 'ID-colaborador', 1)" 
                                autocomplete="OFF" autofocus="ON" required name="ID-colaborador" pattern="[0-9]{1,10}">
                                <div class="form-text"> Campo obligatorio. Solo se admite un número de máximo 10 dígitos. </div>
                            </div>

                            <div class="col-12 col-md-6 mb-4">
                                <label for="primer-nombre" class="form-label fw-semibold"> Primer nombre (*) </label>
                                <input type="text" class="form-control mayusculas-iniciales" id="primer-nombre" 
                                autocomplete="OFF" required name="primer-nombre" pattern="^[a-zA-ZÁÉÍÓÚáéíóúñÑ]{1,}$">
                                <div class="form-text"> Campo obligatorio. Solo se puede introducir una palabra (sin espacios). </div>
                            </div>

                            <div class="col-12 col-md-6 mb-4">
                                <label for="segundo-nombre" class="form-label fw-semibold"> Segundo nombre </label>
                                <input type="text" class="form-control mayusculas-iniciales" id="segundo-nombre" 
                                autocomplete="OFF" name="segundo-nombre" pattern="^[a-zA-ZÁÉÍÓÚáéíóúñÑ ]{1,}$">
                                <div class="form-text"> Campo opcional. Se admiten nombres adicionales. </div>
                            </div>

                            <div class="col-12 col-md-6 mb-4 mb-md-0">
                                <label for="primer-apellido" class="form-label fw-semibold"> Apellido paterno (*) </label>
                                <input type="text" class="form-control mayusculas-iniciales" id="primer-apellido" 
                                autocomplete="OFF" required name="primer-apellido" pattern="^[a-zA-ZÁÉÍÓÚáéíóúñÑ ]{1,}$">
                                <div class="form-text"> Campo obligatorio. Se admiten palabras adicionales</div>
                            </div>

                            <div class="col-12 col-md-6 mb-4">
                                <label for="segundo-apellido" class="form-label fw-semibold"> Apellido materno </label>
                                <input type="text" class="form-control mayusculas-iniciales" id="segundo-apellido" 
                                autocomplete="OFF" name="segundo-apellido" pattern="^[a-zA-ZÁÉÍÓÚáéíóúñÑ ]{1,}$">
                                <div class="form-text"> Campo opcional. Se admiten apellidos adicionales. </div>
                            </div>

                            <div class="col-12 col-lg-6 mb-0">
                                <label for="fecha-nacimiento" class="form-label fw-semibold"> Fecha de nacimiento </label>
                                <input type="date" name="fecha-nacimiento" min="1900-01-01" class="form-control" id="fecha-nacimiento" autocomplete="OFF">
                                <div class="form-text"> 
                                    Campo opcional. La fecha introducida por defecto corresponde 
                                    a 20 años anteriores al día actual del calendario del sistema operativo.
                                </div>
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
                                                echo "<option value='" . $carrera[0]. "'> " . $carrera[1] . " </option> ";
                                            }
                                        }
                                    ?>
                                </select>
                                <div class="form-text"> 
                                    Campo obligatorio.
                                </div>
                            </div>

                            <div class="col-12 col-md-6 mb-4">
                                <label for="modalidades" class="form-label fw-semibold"> Modalidad (*) </label>
                                <select class="form-select" name="modalidad" id="modalidades">
                                    <?php
                                        if(isset($modalidades) && $modalidades->num_rows > 0) {
                                            while($modalidad = $modalidades->fetch_row()) {
                                                echo "<option value='" . $modalidad[0]. "'> " . $modalidad[1] . " </option> ";
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
                                autocomplete="OFF" required name="hora-entrada" placeholder="08:00" value="08:00"
                                pattern="^((0[8-9]|1[0-9]|2[0]):[0-5][0-9])|21:00$"
                                oninput="verificarRangosHoras('hora-entrada', 'hora-salida')">
                                <div class="form-text"> 
                                    Campo obligatorio. Formato de 08:00 a 21:00 horas.
                                </div>
                            </div>

                            <div class="col-12 col-md-6">
                                <label for="hora-salida" class="form-label fw-semibold"> Hora de salida (*) </label>
                                <input type="text" class="form-control mayusculas-iniciales" id="hora-salida" 
                                autocomplete="OFF" required name="hora-salida" placeholder="12:00" value="12:00"
                                pattern="^((0[8-9]|1[0-9]|2[0]):[0-5][0-9])|21:00$"
                                oninput="verificarRangosHoras('hora-entrada', 'hora-salida')">
                                <div class="form-text"> 
                                    Campo obligatorio. Formato de 08:00 a 21:00 horas.
                                </div>
                            </div>

                            <div class="col-12 my-4">
                                <hr class="mb-0 border border-1 border-dark">
                            </div>
                        </div>

                        <!--Botón de registro de colaborador-->
                        <div class="text-center">
                            <button class="btn btn-primary">
                                Registrar colaborador
                            </button>
                        </div>
                    </form>
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
        <script src="../../js/enfocar_recuadro.js"> </script>
        <script src="../../js/dselect.min.js"> </script>
        <script src="../../js/peticiones_ajax/verificar_colaborador.js"> </script>
        <script src="../../js/verificar_rangos_horas.js"> </script>
        <script type="text/javascript">
            document.body.onload = () => {
                document.querySelector("html").classList.remove("invisible");

                // Seleccionar automáticamente el recuadro
                // del identificador del colaborador.
                enfocarRecuadro("ID-colaborador");
            }

            document.getElementById("cierre-sesion").addEventListener("click", () => {
                document.getElementById("formulario").requestSubmit();
            });
            document.getElementById("formulario").addEventListener("submit", confirmarCierreSesion);
            document.getElementById("registro-colaborador").addEventListener("submit", confirmarRegistroColaborador);

            let fechaActual = new Date();
            document.getElementById("fecha-nacimiento").setAttribute("max", fechaActual.toLocaleDateString().split("/").reverse().join("-"));
            fechaActual.setFullYear(fechaActual.getFullYear() - 21);
            document.getElementById("fecha-nacimiento").value = fechaActual.toLocaleDateString().split("/").reverse().join("-");

            dselect(document.getElementById("carreras"), { search: true, maxHeight: "200px" });
            dselect(document.getElementById("modalidades"), { search: false });    
        </script>
    </body>
</html>