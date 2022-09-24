<?php
session_start();


$host_db = "localhost";
 $user_db = "root";
 $pass_db = "";
 $db_name = "checadorus";


 $conexion = new mysqli($host_db, $user_db, $pass_db, $db_name);

 
 if ($conexion->connect_error) {
 die("La conexion falló: " . $conexion->connect_error);
 }
 
 $colaboradores= "SELECT id,nombre,apemat FROM colaboradores"; 
 $colaboradores = mysqli_query($conexion,$colaboradores) or die("error"); 
$colaboradores2= "SELECT id,nombre,apemat FROM colaboradores"; 
 $colaboradores2 = mysqli_query($conexion,$colaboradores2) or die("error"); 

?>

<html lang="en">

<head>
 <title>Contigencias</title>
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

		
<body style="background-color:#01A9DC;">
	
<br>

 <form action="contingencias.php" method="post"> 
	 <fieldset class="izquierda">
     <legend>Registrar Permiso</legend>
          
    		 <select name="colaboradores" style="width:300px" > 
			 <option>Seleccione un Colaborador...</option>
		     <?php 			
			 while($row = mysqli_fetch_array($colaboradores)) {
			 echo "<option value='".$row['id']."'> ".$row['id'].". ".$row['nombre']." ".$row['apemat']."</option>";
			 }
 
			 ?>
		 </select>
	 <br><br><br>
	 	<label for="fechau">Fecha Unica: </label>
	 	<input type="date" name="fechau" step="1" >
	 <br><br><br>
	 	<label >Rango de Fechas </label><br><br>	 
	 	<label >Fecha Inicio: </label>
	 	<input type="date" name="fechai" step="1" >
  
     	<label for="fechaf">Fecha Fin: </label>
	 	<input type="date" name="fechaf" step="1" >
	 <br><br><br>
	 
	 	<label for="fechaf">Observaciones: </label><br>
	 	<TEXTAREA COLS=50 ROWS=10 NAME="obser" > 
	 					</TEXTAREA> 
	 
	 	<br>   <br><br>
 		<input type="submit" name="permiso" value="Registrar Permiso">
	 </fieldset>
	</form>
	
	

 <form action="contingencias.php" method="post"> 
	 <fieldset class="derecha">
     <legend>Registrar Contingencia</legend>
          
    		 <select name="colaboradores2" style="width:300px" > 
			 <option>Seleccione un Colaborador...</option>
		 <?php 			
			 while($row = mysqli_fetch_array($colaboradores2)) {
			 echo "<option value='".$row['id']."'> ".$row['id'].". ".$row['nombre']." ".$row['apemat']."</option>";
			 }
 
			 ?>
		 </select>
	 <br><br><br>
	 	<label >Fecha: </label>
	 	<input type="date" name="fechau2" step="1" required>
	 <br><br><br>
	 	<label >Horas </label><br><br>	 
	 	<label >Hora Inicio: </label>
		 <input type="time" name="ce" required>
  
     	<label>Hora Fin: </label>
	 	 <input type="time" name="cs" required>
	 <br><br><br>
	 
    
	 	<label >Observaciones: </label><br>
	 	<TEXTAREA COLS=50 ROWS=10 NAME="obser2"  > 
	 					</TEXTAREA> 
	 
	 	<br>   <br><br>
 		<input type="submit" name="contingencia" value="Registrar Contingencia">
	 </fieldset>
	</form>
	
	<a href="logout.php" ><img class="mi-imagen-abajo-derecha" src="img/cs.png" width="50" height="50"   /></a>
	</body>
	
	<?php 
		
		
	 if (isset($_POST['permiso']))
	 {		 
				 
		 if(!empty($_POST['fechai']))
		 {
	
			 if(!empty($_POST['fechaf']))
			 {
				 
				$id=$_POST['colaboradores'];
		 		$fechai= $_POST['fechai'];
		 		$fechaf= $_POST['fechaf'];
		 		$observ=$_POST['obser'];
				
				 for($i=$fechai;$i<=$fechaf;$i = date("Y-m-d", strtotime($i."+ 1 days")))
				 {
    			     
					  $query = "INSERT INTO contingencia (id_colaborador, fecha, observaciones)

           						VALUES ('$id', '$i', '$observ')";
   
        			  mysqli_query($conexion,$query) or die ("Error");  

				 }
			 }
		 }else{
			 
		  	if(!empty($_POST['fechau']))
		 	{ 
				$id=$_POST['colaboradores'];
		 		$fechau= $_POST['fechau'];
		 		$observ=$_POST['obser'];
				$query2 = "INSERT INTO contingencia (id_colaborador, fecha, observaciones,tipo)

           						VALUES ('$id', '$fechau', '$observ',1)";
   
        		mysqli_query($conexion,$query2) ;  
			 
		 	}
		 }
		
	 }
	
	 if (isset($_POST['contingencia']))
	 {
		 $id=$_POST['colaboradores2'];
		 $fecha= $_POST['fechau2'];
		 $ce= $_POST['ce'];
		 $cs= $_POST['cs'];
		 $observ=$_POST['obser2'];
		 $horasreali=RestarHoras($ce,$cs);
		 
		 $query2 = "INSERT INTO contingencia (id_colaborador, c_entrada, c_salida, fecha, hora_e, observaciones,tipo)

           						VALUES ('$id','$ce','$cs','$fecha', '$horasreali', '$observ',0)";
   
         mysqli_query($conexion,$query2) or die ("Algo salio mal");  
		 
	  
	  
	 }
	
	function RestarHoras($horaini,$horafin)
	{
	$horai=substr($horaini,0,2);
	$mini=substr($horaini,3,2);
	$segi=substr($horaini,6,2);
 
	$horaf=substr($horafin,0,2);
	$minf=substr($horafin,3,2);
	$segf=substr($horafin,6,2);
 
	$ini=((($horai*60)*60)+($mini*60)+$segi);
	$fin=((($horaf*60)*60)+($minf*60)+$segf);
 
	$dif=$fin-$ini;
 
	$difh=floor($dif/3600);
	$difm=floor(($dif-($difh*3600))/60);
	$difs=$dif-($difm*60)-($difh*3600);
	return date("H:i:s",mktime($difh,$difm,$difs));
	}
	
	
	
	?>
</html>
	
 
