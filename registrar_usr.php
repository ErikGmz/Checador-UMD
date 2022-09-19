<?php
 $host_db = "localhost";
 $user_db = "root";
 $pass_db = "";
 $db_name = "checadorus";
 $tbl_name = "colaboradores"; 


 $conexion = new mysqli($host_db, $user_db, $pass_db, $db_name);

 
 if ($conexion->connect_error) {
 die("La conexion falló: " . $conexion->connect_error);
 }
?>

<html lang="en">

<head>
 <title>Registrar Colaborador</title>
 <meta charset = "utf-8">
	<style>
	.mi-imagen-abajo-derecha{
    position:absolute;
    bottom:5px;
    right:10px;
}		
		form fieldset {
  			width: 45%;
			border: 2px solid #00F;
			border-radius: 8px;
			padding: 27px;
			padding-bottom: 27px;
			
		}
		form legend {
font-weight: bold;
font-size: 1.5em;
color: #03F;
border: 1px solid #03F;
padding: 5px;
			
}
		.izquierda {float:left;
		}
.derecha {float:right;}
		
		 #fondo
{position: absolute; top: 0; left: 0; width: 100%; z-index: -1}
    </style>
</head>

		
<body style="overflow-y:hidden">
    <img id="fondo" src="img/Checador_centro.png" alt="background image" />
	
<br><br>
    <form action="registrar_usr.php" method="post"> 
		<fieldset class="izquierda">
            <legend>Registrar Nuevo Colaborador</legend>
          
    
    
 <label for="nombre">Id. Colaborador: </label><br>
 <input type="text" name="id" maxlength="32" required>
 <br><br>
			<table>
			<td> <label for="nombre">Nombre: </label><br>
 <input type="text" name="nombre" maxlength="32" required>
				<br><br></td>

 <td><label for="apellpat">Apellido Paterno: </label><br>
 <input type="text" name="apepat" maxlength="32" required>
 <br><br>
				</td>
    <td>  <label for="apellmat">Apellido Materno: </label><br>
 <input type="text" name="apemat" maxlength="32" required>
 <br><br>
				</td>
         </table>
     
		               <label for="carrer">Carrera: </label><br>
 <input type="text" name="carrer" maxlength="32" required>
 <br><br>   
     
     <label for="modal">Modalidad: </label><br>
 <input type="text" name="modal" maxlength="32" required>
 <br><br>
	
		
		
             <label for="turno">Turno: </label><br>
 <input type="text" name="turno" maxlength="32" required>
 <br><br>
     <table>
		 <td >
     <label for="he">Hora Entrada: </label><br>
    <input type="time" name="he">
</td>
     <td>
     <label for="hs">  Hora Salida: </label><br>
     <input type="time" name="hs">
		 </td>
			</table>
 <br><br>
	
 <input type="submit" name="nuevocol" value="Dar de Alta">
 <input type="reset" name="clear" value="Borrar">
		 <br><br>
		</fieldset>
</form>
		
			
		 <form action="registrar_usr.php" method="post"> 
		<fieldset class="derecha">
			
            <legend>Registrar Horario Secundario</legend>
         <br>
			<label for="nombre">Id. Colaborador: </label><br>
 <input type="text" name="id" maxlength="32" required>
 <br><br>
     <table>
		 <td >
     <label for="he2">Hora Entrada: </label>
    <input type="time" name="he2">
</td>
     <td>
     <label for="hs2">  Hora Salida: </label>
     <input type="time" name="hs2">
		 </td>
			</table>
 <br><br>
	
 <input type="submit" name="nuevohorario" value="Agregar Horario">
				</fieldset>
	</form>
	<br>
	 <form action="modifica_usr.php" method="post"> 
		 <br>
		<fieldset class="derecha">
			
            <legend>Modificar Usuario</legend>
         <br>
			<label for="nombre">Id. Colaborador: </label><br>
 <input type="text" name="id" maxlength="32" required>
 <br><br>
     
	
 <input type="submit" name="submit" value="Modificar">
				</fieldset>
	</form>


<?php

if (isset($_POST['nuevocol'])){

	$id = $_POST['id'];
$nombre = $_POST['nombre'];
$apepat = $_POST['apepat'];
$apemat = $_POST['apemat'];
$carrera = $_POST['carrer'];
$modalidad = $_POST["modal"];
$turno = $_POST['turno'];
$he = $_POST['he'];
$hs = $_POST['hs'];


  $query = "INSERT INTO colaboradores (id, nombre, apepat, apemat, carrera, modalidad)

           VALUES ('$id', '$nombre','$apepat','$apemat','$carrera','$modalidad')";
   
        mysqli_query($conexion,$query) or die ("UPPS ALGO SALIO MAL");  
	
	 $query2 = "INSERT INTO horarios (id, turno, hora_entrada,hora_salida)

           VALUES ('$id','$turno','$he','$hs')";
   
        mysqli_query($conexion,$query2) or die ("UPPS ALGO SALIO MAL");  


}

if (isset($_POST['nuevohorario'])){

 $id = $_POST['id'];
$he2 = $_POST['he2'];
$hs2 = $_POST['hs2'];


   $query3 = "UPDATE horarios SET entrada_secu='$he2',salida_secu='$hs2' where id='$id'";
   
        mysqli_query($conexion,$query3) or die ("UPPS ALGO SALIO MAL");  


}


 mysqli_close($conexion);



?>
<a href="logout.php" ><img class="mi-imagen-abajo-derecha" src="img/cs.png" width="50" height="50"   /></a>

 </body>
</html>