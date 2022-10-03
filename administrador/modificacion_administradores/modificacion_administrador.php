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

    # Obtener todos los administradores registrados.
    $administradores = $conexion_base->query("SELECT * FROM coordinador;");
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
        <link rel="stylesheet" href="../../css/clases_globales.css">
        <link rel="stylesheet" href="../../css/desplazamiento_instantaneo.css">
        <link rel="stylesheet" href="../../css/estilos_administradores.css">
        <link rel="stylesheet" href="../../css/bootstrap/bootstrap-icons.css">
        <link rel="stylesheet" href="../../css/bootstrap/bootstrap.min.css">
        <link rel="stylesheet" href="../../css/dselect.min.css">
        
        <!--Título de la página-->
        <title> Modificación de administrador </title>

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
                        Modificación de administrador del sistema
                    </h1>
                    <hr class="my-4 border border-1 border-dark">

                    <!--Formulario de selección del ID de un administrador registrado-->
                    <?php
                    if(isset($administradores) && $administradores->num_rows > 0) {
                    ?>
                    <form method="GET" action="<?=$_SERVER['PHP_SELF']?>" class="mb-0 px-0 px-md-5">
                        <h5 class="text-center mb-3"> Selección del administrador a modificar </h5>
                        <select class="form-select mb-4" name="ID-administrador" id="administradores" required>
                            <?php
                                if(isset($administradores) && $administradores->num_rows > 0) {
                                    $numero_administrador = 0;
                                    while($administrador = $administradores->fetch_row()) {
                                        if(@$_GET["ID-administrador"] == $administrador[0]) {
                                            echo "<option selected value='" . $administrador[0] . "'> Administrador no. " 
                                            . ++$numero_administrador . " - " . $administrador[0] . " </option> ";
                                        }
                                        else {
                                            echo "<option value='" . $administrador[0] . "'> Administrador no. " 
                                            . ++$numero_administrador . " - " . $administrador[0] . " </option> ";
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
                    if(isset($_GET["ID-administrador"])) {
                        # Buscar al administrador introducido 
                        # en la lista de búsqueda.
                        if($administrador = $conexion_base->query("SELECT * FROM coordinador
                        WHERE ID = '" . $_GET["ID-administrador"] . "';")) {
                            if($administrador->num_rows > 0) {
                                $datos_administrador = $administrador->fetch_row();
                                $valido = true;
                                ?>
                                <hr class="my-4 border border-1 border-dark">
                                <?php
                    ?>
                    <form method="POST" action="procesar_modificacion_administrador.php" 
                    class="mb-0" id="modificacion-administrador">
                        <div class="row mb-2">
                            <!--Datos nuevos del administrador-->
                            <div class="col-12 text-center mb-4">
                                <h4 class="mb-0"> Datos nuevos del administrador </h4>
                            </div>

                            <div class="col-12 col-md-6 mb-4 mb-md-0">
                                <label for="ID-administrador" class="form-label fw-semibold"> ID del administrador (*) </label>
                                <input type="text" class="form-control" id="ID-administrador" 
                                onchange="verificarAdministrador(document.getElementById('ID-administrador').value, 'ID-administrador', 2)" 
                                autocomplete="OFF" autofocus="ON" required name="ID-administrador" pattern="[0-9]{1,10}"
                                value="<?=$_GET["ID-administrador"]?>">
                                <div class="form-text"> Campo obligatorio. Solo se admite un número de máximo 10 dígitos. </div>
                            </div>

                            <div class="col-12 col-md-6 mb-4">
                                <label for="clave-original" class="form-label fw-semibold"> Contraseña original (*) </label>
                                <input type="password" class="form-control" id="clave-original" 
                                autocomplete="OFF" required name="clave-original" maxlength="30"
                                <?php
                                    echo " onchange=\"verificarClaveOriginal(document.getElementById('clave-original').value, 'clave-original', '"
                                    . $_GET["ID-administrador"] . "')\""; 
                                ?>>
                                <div class="form-text"> Campo obligatorio. Se puede introducir un máximo de 30 caracteres. </div>
                            </div>

                            <div class="col-12 col-md-6 mb-4 mb-md-0">
                                <label for="clave-nueva" class="form-label fw-semibold"> Contraseña nueva (*) </label>
                                <input type="password" class="form-control" id="clave-nueva" 
                                autocomplete="OFF" required name="clave-nueva" maxlength="30"
                                onchange="verificarClavesIguales('clave-nueva', 'confirmacion-clave-nueva')">
                                <div class="form-text"> Campo obligatorio. Se puede introducir un máximo de 30 caracteres. </div>
                            </div>

                            <div class="col-12 col-md-6 mb-0">
                                <label for="confirmacion-clave-nueva" class="form-label fw-semibold"> Confirmación de contraseña (*) </label>
                                <input type="password" class="form-control" id="confirmacion-clave-nueva" 
                                autocomplete="OFF" required name="confirmacion-clave-nueva" maxlength="30"
                                onchange="verificarClavesIguales('clave-nueva', 'confirmacion-clave-nueva')">
                                <div class="form-text"> Campo obligatorio. Se puede introducir un máximo de 30 caracteres. </div>
                            </div>

                            <div class="col-12 my-4">
                                <hr class="mb-0 border border-1 border-dark">
                            </div>
                        </div>
                        <input type="hidden" id="anterior-ID-administrador" name="anterior-ID-administrador" value="<?=$_GET["ID-administrador"]?>">

                        <!--Botón de registro de colaborador-->
                        <div class="text-center">
                            <button class="btn btn-primary">
                                Modificar administrador
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
                                                El administrador no fue encontrado
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
                                            El administrador no fue encontrado
                                        </span>
                                    </h4>
                                <?php
                            }
                            @$administrador->close();
                        }
                    }
                    else {
                    ?>
                        <h4 class="text-center mt-4 mb-0">
                            <span class="badge bg-danger py-3">
                                No hay administradores a modificar
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
        <script src="../../js/peticiones_ajax/verificar_administrador.js"> </script>
        <script src="../../js/peticiones_ajax/verificar_clave_original.js"> </script>
        <script src="../../js/verificar_rangos_horas.js"> </script>
        <script src="../../js/verificar_claves.js"> </script>
        <script type="text/javascript">
            <?php
                if(isset($administradores, $_GET["ID-administrador"], $valido) && $administradores->num_rows > 0 && @$valido) {
                    echo "onchange=verificarClaveOriginal(document.getElementById('clave-original').value, 'clave-original', '"
                    . $_GET["ID-administrador"] . "')"; 
                }
            ?>

            document.getElementById("cierre-sesion").addEventListener("click", () => {
                document.getElementById("formulario").requestSubmit();
            });
            document.getElementById("formulario").addEventListener("submit", confirmarCierreSesion);
            
            <?php
            if(isset($administradores) && $administradores->num_rows > 0) {
            ?>
                dselect(document.getElementById("administradores"), { search: true, maxHeight: "200px" });
            <?php
                if(isset($_GET["ID-administrador"], $valido) && @$valido) {
                ?>
                    document.getElementById("modificacion-administrador").addEventListener("submit", confirmarModificacionAdministrador);
                <?php
                }
            }
            ?>
        </script>
    </body>
</html>