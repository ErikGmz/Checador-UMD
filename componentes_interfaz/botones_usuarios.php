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

<div class="mb-2 mb-md-0 col-12 d-flex justify-content-around align-items-center justify-content-md-evenly">
    <div class="row botones">
        <?php
        if(basename($_SERVER['SCRIPT_FILENAME']) == "listado_horas.php") {
        ?>
            <div class="mb-3 col-12 col-sm-6 centrado-flex">
                <button class="btn btn-light text-muted" onclick="location.href = 'solicitud_usuario.php'"> 
                    <span class="fs-6 fw-semibold"> <i class="bi bi-calendar-date-fill me-1"> </i> Consultar horas </span>
                </button>
            </div>

            <div class="mb-3 col-12 col-sm-6 centrado-flex">
                <button class="btn btn-light text-muted" onclick="location.href = '../index.php'"> 
                    <span class="fs-6 fw-semibold"> <i class="bi bi-clock-fill me-1"> </i> Registrar actividad </span>
                </button>
            </div>

            <div class="col-12 col-sm-6 col-lg-12 centrado-flex">
                <button class="btn btn-light text-muted" onclick="location.href = '../administrador/gestion_sesion/inicio_sesion.php'"> 
                    <span class="fs-6 fw-semibold"> <i class="bi bi-key-fill me-1"> </i> Modo administrador </span>
                </button>
            </div>
        <?php
        }
        else {
            if(basename($_SERVER['SCRIPT_FILENAME']) == "index.php") {
        ?>
            <div class="mb-3 mb-sm-0 col-12 col-sm-6 mb-lg-4 col-lg-12 centrado-flex">
                <button class="btn btn-light text-muted" onclick="location.href = 'revision_horas/solicitud_usuario.php'"> 
                    <span class="fs-6 fw-semibold"> <i class="bi bi-calendar-date-fill me-1"> </i> Consultar horas </span>
                </button>
            </div>

            <div class="col-12 col-sm-6 col-lg-12 centrado-flex">
                <button class="btn btn-light text-muted" onclick="location.href = 'administrador/gestion_sesion/inicio_sesion.php'"> 
                    <span class="fs-6 fw-semibold"> <i class="bi bi-key-fill me-1"> </i> Modo administrador </span>
                </button>
            </div>
        <?php
            }
            else {
                if(basename($_SERVER['SCRIPT_FILENAME']) == "inicio_sesion.php") {
                    ?>
                        <div class="mb-3 mb-sm-0 col-12 col-sm-6 mb-lg-4 col-lg-12 centrado-flex">
                            <button class="btn btn-light text-muted" onclick="location.href = '../../index.php'"> 
                                <span class="fs-6 fw-semibold"> <i class="bi bi-clock-fill me-1"> </i> Registrar actividad </span>
                            </button>
                        </div>

                        <div class="col-12 col-sm-6 col-lg-12 centrado-flex">
                            <button class="btn btn-light text-muted" onclick="location.href = '../../revision_horas/solicitud_usuario.php'"> 
                                <span class="fs-6 fw-semibold"> <i class="bi bi-calendar-date-fill me-1"> </i> Consultar horas </span>
                            </button>
                        </div>
                    <?php
                }
                else {
                    ?>
                        <div class="mb-3 mb-sm-0 col-12 col-sm-6 mb-lg-4 col-lg-12 centrado-flex">
                            <button class="btn btn-light text-muted" onclick="location.href = '../index.php'"> 
                                <span class="fs-6 fw-semibold"> <i class="bi bi-clock-fill me-1"> </i> Registrar actividad </span>
                            </button>
                        </div>

                        <div class="col-12 col-sm-6 col-lg-12 centrado-flex">
                            <button class="btn btn-light text-muted" onclick="location.href = '../administrador/gestion_sesion/inicio_sesion.php'"> 
                                <span class="fs-6 fw-semibold"> <i class="bi bi-key-fill me-1"> </i> Modo administrador </span>
                            </button>
                        </div>
                    <?php
                }
            }
        }
        ?>
    </div>
</div>