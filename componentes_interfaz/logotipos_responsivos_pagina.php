<?php
    switch(basename($_SERVER['SCRIPT_FILENAME'])) {
        case "index.php":
        case "solicitud_usuario.php":
        case "listado_horas.php":
        case "inicio_sesion.php":
        break;

        default:
            header("location: ../index.php");
            die();
        break;
    }
?>

<div class="logotipos col-12 col-lg-4 mb-4 mb-lg-0">
    <div class="col-12">
        <div class="row interfaz">      
            <!--Logotipos de la página-->
            <div class="mb-4 mb-md-5 mb-lg-0 col-12 col-md-6 col-lg-12 centrado-flex">
                <img class="img-fluid" id="logouaa-claro">
            </div>

            <div class="mb-5 mb-md-5 mb-lg-0 col-12 col-md-6 col-lg-12 centrado-flex">
                <img class="img-fluid" id="logoUMD">
            </div>

            <!--Botones-->
            <?php
                include("botones_usuarios.php");
            ?>
        </div>
    </div>
</div>