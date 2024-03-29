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

    # Obtener todos los administradores del sistema.
    $administradores = $conexion_base->query("SELECT * FROM coordinador;");
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

        <!--Título de la página-->
        <title> Listado de administradores </title>

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
                        Listado de administradores del sistema
                    </h1>
                    <hr class="my-4 border border-1 border-dark">

                    <?php
                    if(isset($administradores) && @$administradores->num_rows > 0) {
                    ?>
                    <div class="rounded-2 text-center fondo-pantone-azul-intermedio mx-auto"> 
                        <h6 class="text-center text-white fondo-pantone-azul-oscuro py-3 rounded-top mb-0 px-3"> 
                            Listado de administradores
                        </h6>
                        
                        <div class="table-responsive fondo-pantone-azul-intermedio rounded tabla-desglose p-3">
                            <table class="table table-striped mb-0">
                                <thead>
                                    <tr">
                                        <th scope="col"> Número de administrador </th>
                                        <th scope="col"> ID del administrador </th>
                                    </tr>
                                </thead>

                                <tbody class="table-group-divider">
                                    <tr>
                                        <?php
                                        $numero_administrador = 0;
                                        while($administrador = $administradores->fetch_row()) {
                                            echo "<tr> ";
                                            echo "<th scope='row' class='py-3'> " . ++$numero_administrador . " </th> ";
                                            echo "<td class='py-3'> " . $administrador[0] .  " </td> ";
                                            echo " </tr>";
                                        }
                                        ?>
                                    </tr>
                                </tbody>
                            </table>
                            <?php
                            }
                            else {
                            ?>
                                <p class="mb-0 fs-5 fw-semibold py-3 text-center"> 
                                    No hay administradores registrados en el sistema
                                </p>
                            <?php   
                            }

                            # Cerrar la conexión con la base de datos.
                            $conexion_base->close();
                            ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!--Scripts de la página-->
        <script src="../../js/confirmar_operaciones.js"> </script>
        <script src="../../js/sweetalert2/sweetalert2@11.js"> </script>
        <script src="../../js/bootstrap/jquery-3.6.0.min.js"> </script>
        <script src="../../js/bootstrap/bootstrap.bundle.min.js"> </script>
        <script type="text/javascript">       
            document.body.onload = () => {
                document.querySelector("html").classList.remove("invisible");
            }

            document.getElementById("cierre-sesion").addEventListener("click", () => {
                document.getElementById("formulario").requestSubmit();
            });
            document.getElementById("formulario").addEventListener("submit", confirmarCierreSesion);
        </script>
    </body>
</html>