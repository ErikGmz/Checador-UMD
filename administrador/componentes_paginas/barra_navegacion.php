<?php
    switch(basename($_SERVER['SCRIPT_FILENAME'])) {
        case "menu_administrador.php":
        case "listado_colaboradores.php":
        case "adicion_colaborador.php":
        case "modificacion_colaborador.php":
        case "eliminacion_colaborador.php":
        case "desbloquear_ID.php":
        case "bloquear_ID.php":
        case "reporte_horas.php":
        case "listado_contingencias.php":
        case "adicion_contingencia.php":
        case "modificacion_contingencia.php":
        case "eliminacion_contingencia.php":
        case "listado_administradores.php":
        case "adicion_administrador.php":
        case "modificacion_administrador.php":
        case "eliminacion_administrador.php":
        break;

        default:
            header("location: ../menu_principal/menu_administrador.php");
            die();
        break;
    }
?>

<nav class="navbar sticky-top mt-0 navbar-expand-lg navbar-dark fondo-pantone-verde-muy-oscuro py-lg-3">
    <div class="container-fluid">
        <a class="navbar-brand"> 
            <img id="logouaa-umd">
        </a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"> </span>
        </button>

        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item me-0 me-lg-2">
                    <a class="nav-link fw-semibold <?php if(basename($_SERVER['SCRIPT_FILENAME']) == "menu_administrador.php") 
                    echo "active"; else echo "\" href=\"../menu_principal/menu_administrador.php"?>" 
                    aria-current="page"> 
                        Inicio 
                    </a>
                </li>

                <li class="nav-item dropdown me-0 me-lg-2">
                    <a class="nav-link dropdown-toggle fw-semibold
                    <?php if(basename($_SERVER['SCRIPT_FILENAME']) == "listado_colaboradores.php"
                    || basename($_SERVER['SCRIPT_FILENAME']) == "adicion_colaborador.php"
                    || basename($_SERVER['SCRIPT_FILENAME']) == "modificacion_colaborador.php"
                    || basename($_SERVER['SCRIPT_FILENAME']) == "eliminacion_colaborador.php"
                    || basename($_SERVER['SCRIPT_FILENAME']) == "desbloquear_ID.php"
                    || basename($_SERVER['SCRIPT_FILENAME']) == "bloquear_ID.php"
                    || basename($_SERVER['SCRIPT_FILENAME']) == "reporte_horas.php")
                    echo "active"?>" 
                    role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        Colaboradores
                    </a>

                    <ul class="dropdown-menu">
                        <li> <a class="dropdown-item <?php if(basename($_SERVER['SCRIPT_FILENAME']) == "listado_colaboradores.php") 
                        echo "fw-semibold active"; else echo "\" href=\"../consulta_colaboradores/listado_colaboradores.php"?>"> 
                        Listado de colaboradores </a> </li>
                        
                        <li> <a class="dropdown-item <?php if(basename($_SERVER['SCRIPT_FILENAME']) == "adicion_colaborador.php") 
                        echo "fw-semibold active"; else echo "\" href=\"../adicion_colaboradores/adicion_colaborador.php"?>"> 
                        Adición de colaborador </a> </li>

                        <li> <a class="dropdown-item <?php if(basename($_SERVER['SCRIPT_FILENAME']) == "modificacion_colaborador.php") 
                        echo "fw-semibold active"; else echo "\" href=\"../modificacion_colaboradores/modificacion_colaborador.php"?>"> 
                        Modificación de colaborador </a> </li>

                        <li> <a class="dropdown-item <?php if(basename($_SERVER['SCRIPT_FILENAME']) == "eliminacion_colaborador.php") 
                        echo "fw-semibold active"; else echo "\" href=\"../eliminacion_colaboradores/eliminacion_colaborador.php"?>"> 
                        Eliminación de colaborador </a> </li>

                        <li> <hr class="dropdown-divider"> </li>
                        
                        <li> <a class="dropdown-item <?php if(basename($_SERVER['SCRIPT_FILENAME']) == "desbloquear_ID.php") 
                        echo "fw-semibold active"; else echo "\" href=\"../operaciones_adicionales_colaborador/desbloquear_ID.php"?>"> 
                        Desbloquear ID </a> </li>

                        <li> <a class="dropdown-item <?php if(basename($_SERVER['SCRIPT_FILENAME']) == "bloquear_ID.php") 
                        echo "fw-semibold active"; else echo "\" href=\"../operaciones_adicionales_colaborador/bloquear_ID.php"?>"> 
                        Bloquear ID </a> </li>

                        <li> <a class="dropdown-item <?php if(basename($_SERVER['SCRIPT_FILENAME']) == "reporte_horas.php") 
                        echo "fw-semibold active"; else echo "\" href=\"../operaciones_adicionales_colaborador/reporte_horas.php"?>"> Reporte de horas </a> </li>
                    </ul>
                </li>

                <li class="nav-item dropdown me-0 me-lg-2">
                    <a class="nav-link dropdown-toggle fw-semibold
                    <?php if(basename($_SERVER['SCRIPT_FILENAME']) == "listado_contingencias.php"
                    || basename($_SERVER['SCRIPT_FILENAME']) == "adicion_contingencia.php"
                    || basename($_SERVER['SCRIPT_FILENAME']) == "modificacion_contingencia.php"
                    || basename($_SERVER['SCRIPT_FILENAME']) == "eliminacion_contingencia.php")
                    echo "active"?>" 
                    role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        Contingencias
                    </a>

                    <ul class="dropdown-menu">
                        <li> <a class="dropdown-item <?php if(basename($_SERVER['SCRIPT_FILENAME']) == "listado_contingencias.php") 
                        echo "fw-semibold active"; else echo "\" href=\"../consulta_contingencias/listado_contingencias.php"?>">  
                        Listado de contingencias </a> </li>

                        <li> <a class="dropdown-item <?php if(basename($_SERVER['SCRIPT_FILENAME']) == "adicion_contingencia.php") 
                        echo "fw-semibold active"; else echo "\" href=\"../adicion_contingencias/adicion_contingencia.php"?>">  
                        Adición de contingencia </a> </li>

                        <li> <a class="dropdown-item <?php if(basename($_SERVER['SCRIPT_FILENAME']) == "modificacion_contingencia.php") 
                        echo "fw-semibold active"; else echo "\" href=\"../modificacion_contingencias/modificacion_contingencia.php"?>">   
                        Modificación de contingencia </a> </li>

                        <li> <a class="dropdown-item <?php if(basename($_SERVER['SCRIPT_FILENAME']) == "eliminacion_contingencia.php") 
                        echo "fw-semibold active"; else echo "\" href=\"../eliminacion_contingencias/eliminacion_contingencia.php"?>">
                        Eliminación de contingencia </a> </li>
                    </ul>
                </li>

                <li class="nav-item dropdown me-0 me-lg-2">
                    <a class="nav-link dropdown-toggle fw-semibold
                    <?php if(basename($_SERVER['SCRIPT_FILENAME']) == "listado_administradores.php"
                    || basename($_SERVER['SCRIPT_FILENAME']) == "adicion_administrador.php"
                    || basename($_SERVER['SCRIPT_FILENAME']) == "modificacion_administrador.php"
                    || basename($_SERVER['SCRIPT_FILENAME']) == "eliminacion_administrador.php")
                    echo "active"?>"
                    role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        Administradores
                    </a>

                    <ul class="dropdown-menu">
                        <li> <a class="dropdown-item <?php if(basename($_SERVER['SCRIPT_FILENAME']) == "listado_administradores.php") 
                        echo "fw-semibold active"; else echo "\" href=\"../consulta_administradores/listado_administradores.php"?>">  
                        Listado de administradores </a> </li>

                        <li> <a class="dropdown-item <?php if(basename($_SERVER['SCRIPT_FILENAME']) == "adicion_administrador.php") 
                        echo "fw-semibold active"; else echo "\" href=\"../adicion_administradores/adicion_administrador.php"?>">  
                        Adición de administrador </a> </li>

                        <li> <a class="dropdown-item <?php if(basename($_SERVER['SCRIPT_FILENAME']) == "modificacion_administrador.php") 
                        echo "fw-semibold active"; else echo "\" href=\"../modificacion_administradores/modificacion_administrador.php"?>">   
                        Modificación de administrador </a> </li>

                        <li> <a class="dropdown-item <?php if(basename($_SERVER['SCRIPT_FILENAME']) == "eliminacion_administrador.php") 
                        echo "fw-semibold active"; else echo "\" href=\"../eliminacion_administradores/eliminacion_administrador.php"?>">
                        Eliminación de administrador </a> </li>
                    </ul>
                </li>

                <li class="nav-item">
                    <form method="POST" id="formulario" action="../gestion_sesion/cerrar_sesion.php" 
                    class="mb-0">
                        <a class="nav-link fw-semibold" id="cierre-sesion" href="#"> 
                            Cerrar sesión 
                        </a>
                        
                        <input type="hidden" name="cierre-sesion" value="1">
                    </form>
                </li>
            </ul>
        </div>
    </div>
</nav>