<?php

$host_db = "localhost";
$user_db = "root";
$pass_db = "";
$db_name = "checadorus";
$tbl_name = "colaboradores";
$conexion = new mysqli($host_db, $user_db, $pass_db, $db_name);

    $idp = $_POST['id'];

 if ($conexion->connect_error) {
 die("La conexion falló: " . $conexion->connect_error);
 }

	
 

  $colaboradores= "SELECT * FROM colaboradores where id='$idp'"; 
  $colaboradores = mysqli_query($conexion,$colaboradores) or die("error");
	
			
			 while($row = mysqli_fetch_assoc($colaboradores)) {
			   $id=$row['id']."";
			 $nom=$row['nombre']."";
				  $apepat=$row['apepat']."";
				  $apemat=$row['apemat']."";
				  $carrera=$row['carrera']."";
				 $modalidad=$row['modalidad']."";
					
			 }

$colaboradoresh= "SELECT turno,hora_entrada,hora_salida,entrada_secu,salida_secu FROM horarios where id='$idp'"; 
  $colaboradoresh = mysqli_query($conexion,$colaboradoresh) or die("error");
	
			
			 while($row = mysqli_fetch_assoc($colaboradoresh)) {
			   
			 		$turno=$row['turno']."";
				  $he=$row['hora_entrada']."";
				  $hs=$row['hora_salida']."";
				  $hes=$row['entrada_secu']."";
				 $hss=$row['salida_secu']."";
					
			 }

   

?>


<html lang="en">

<head>
 <title>Registrar Colaborador</title>
 <meta charset = "utf-8">
	<style>
	
		form fieldset {
  width: 45%;
			border: 2px solid #00F;
border-radius: 8px;
}
		form legend {
font-weight: bold;
font-size: 1.5em;
color: #03F;
border: 1px solid #03F;
padding: 5px;
}
		.izquierda {float:left;}
.derecha {float:right;}
		
		 #fondo
{position: absolute; top: 0; left: 0; width: 100%; z-index: -1}
    </style>
</head>

		
<body style="overflow-y:hidden">
<img id="fondo" src="img/Checador_centro.png" alt="background image" />
	
<br><br>
    <form action="modifica_usr.php" method="post"> 
    
		<fieldset class="izquierda">
        <legend>Modificar Datos Personales</legend>
    
   
 <label for="nombre">Id. Colaborador: </label><br>
 <input type="text" name="id" maxlength="32" value="<?php echo $id; ?>" readonly="readonly" required>
			
 <br><br>
			    <label for="nombre">Nombre: </label><br>
 <input type="text" name="nombre" maxlength="32" value="<?php echo $nom; ?>" required>
 <br><br>

 <label for="apellpat">Apellido Paterno: </label><br>
 <input type="text" name="apepat" maxlength="32" value="<?php echo $apepat; ?>" required>
 <br><br>
     
      <label for="apellmat">Apellido Materno: </label><br>
 <input type="text" name="apemat" maxlength="32" value="<?php echo $apemat; ?>" required>
 <br><br>
		
     
     
		          
       <label for="carrer">Carrera: </label><br>
 <input type="text" name="carrer" maxlength="32" value="<?php echo $carrera; ?>" required>
 <br><br>   
     
     <label for="modal">Modalidad: </label><br>
 <input type="text" name="modal" maxlength="32" value="<?php echo $modalidad; ?>" required>
 <br><br>
		
			
		
            
     <label for="turno">Turno: </label><br>
 <input type="text" name="turno" maxlength="32" value="<?php echo $turno; ?>" required>
 <br><br>
     <table>
		 <td >
     <label for="he">Hora Entrada: </label>
    <input type="time" name="he" value="<?php echo $he; ?>">
</td>
     <td>
     <label for="hs">  Hora Salida: </label>
     <input type="time" name="hs" value="<?php echo $hs; ?>">
		 </td>
			</table>
 <br><br>
	 
 
		
			
				</fieldset>
			
		 
		
	
		<fieldset class="derecha">
			
            <legend>Modificar Horario Secundario</legend>
        
     <table>
		 <td >
     <label for="he2">Hora Entrada: </label>
    <input type="time" name="he2" value="<?php echo $hes; ?>">
</td>
     <td>
     <label for="hs2">  Hora Salida: </label>
     <input type="time" name="hs2" value="<?php echo $hss; ?>">
		 </td>
			</table>
 <br><br>
	<input type="submit" name="Modificar" value="Modificar">
 <input type="reset" name="clear" value="Borrar">
			
				</fieldset>
	</form>
	
	
	<?php 
	if (isset($_POST['Modificar'])){
		$modifica1 = "UPDATE colaboradores SET id='".$_POST['id']."', nombre='".$_POST['nombre']."', apepat='".$_POST['apepat']."', apemat='".$_POST['apemat']."', carrera='".$_POST['carrer']."', modalidad='".$_POST['modal']."' where id='".$_POST['id']."'";
   
        mysqli_query($conexion,$modifica1) or die ("UPPS ALGO SALIO MAL"); 
		
		$modificah = "UPDATE horarios SET turno='".$_POST['turno']."', hora_entrada='".$_POST['he']."', hora_salida='".$_POST['hs']."', entrada_secu='".$_POST['he2']."', salida_secu='".$_POST['hs2']."' where id='".$_POST['id']."'";
   
        mysqli_query($conexion,$modificah) or die ("UPPS ALGO SALIO MAL"); 
		
		echo "<script type=\"text/javascript\">alert(\"Registro Modificado Exitosamente...!!!\");</script>";
		 mysqli_close($conexion);
		header("Location: registrar_usr.php");

	
	}
	?>





 </body>
</html>