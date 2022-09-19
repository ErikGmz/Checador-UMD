<?php
session_start();

if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == true) {

} else {
   header("Location: login.php");

exit;
}


?>

<!DOCTYPE html>
<html lang="en">

<head>
<title>Coordinadores</title>
    <style type="text/css">
			
			* {
				margin:0px;
				padding:0px;
			}
			
			#header {
				font-family:Arial, Helvetica, sans-serif;
			}
			
			ul, ol {
				list-style:none;
			}
			
			.nav > li {
				float:left;
			}
			
			.nav li a {
				background-color:#01A9DC;
				color:#fff;
				text-decoration:none;
				padding:21px 45px;
				display:block;
			}
			
			.nav li a:hover {
				background-color:#434343;
			}
			
			
	     #fondo
{position: absolute; top: 0; left: 0; width: 100%; z-index: -1}
    </style>
</head>

<body style="overflow-y:hidden" style="overflow-x:hidden">
<img id="fondo" src="img/Checador_centro.png" alt="background image" />
<div id="header">
	
			<nav> <!-- Aqui estamos iniciando la nueva etiqueta nav -->
				<ul class="nav">
                    <li><a href="">Bienvenido: <?php echo $_SESSION['username']; ?></a></li>
					<li><a href="desbloquearid.php" target="PRINCIPAL">Desbloquear ID</a></li>
                    <li><a href="registrar_usr.php" target="PRINCIPAL">Registrar Colaborador</a></li> 
					<li><a href="elimina-usr.php" target="PRINCIPAL">Eliminar colaboradores</a></li>
					<li><a href="reporteshoras.php" target="PRINCIPAL">Reportes de Horas</a></li>					
                    <li><a href="logout.php" target="_top">Cerrar Sesion</a></li>
				</ul>
			
</div>
    


</body>
</html>
