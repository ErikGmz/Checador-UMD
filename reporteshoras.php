<?php
session_start();

$host_db = "localhost";
 $user_db = "root";
 $pass_db = "";
 $db_name = "checadorus";
 $tbl_name = "colaboradores"; 


 $conexion = new mysqli($host_db, $user_db, $pass_db, $db_name);

 
 if ($conexion->connect_error) {
 die("La conexion falló: " . $conexion->connect_error);
 }
	$colaboradores= "SELECT id,nombre,apemat FROM colaboradores"; 
     $colaboradores = mysqli_query($conexion,$colaboradores) or die("error"); 

?>

<html lang="en">

<head>
 <title>Reportes de Horas</title>
 <meta charset = "utf-8">
		
    <style>
    table { 
  width: 100%; 
  border-collapse: collapse; 
		text-align:center;
}
/* Zebra striping */
tr:nth-of-type(odd) { 
  background: #eee; 
	text-align:center;
}
th { 
  background: #333; 
  color: white; 
  font-weight: bold;
	text-align:center;
}
td, th { 
  padding: 6px; 
  border: 1px solid #ccc; 
  text-align: left; 
	text-align:center;
}
    
    </style>
</head>

<body style="background-color:#01A9DC;">
    
     <br><br>
<center>
 <form action="reporteshoras.php" method="post"> 
<table>
     <td>

		 <select name="colaboradores" style="width:300px" > 
			 <option>Seleccione un Colaborador...</option>
		 <?php 			
			 while($row = mysqli_fetch_array($colaboradores)) {
			 echo "<option value='".$row['id']."'> ".$row['id'].". ".$row['nombre']." ".$row['apemat']."</option>";
			 }
 
			 ?>
		 </select>
    </td>
<td>
<label for="fechai">Fecha Inicio: </label>
<input type="date" name="fechai" step="1" >
    </td>
    <td>
     <label for="fechaf">Fecha Fin: </label>
<input type="date" name="fechaf" step="1" >
    </td>
     
<td>
 <input type="submit" name="reporteid" value="Reporte Individual">
	
    </td>
<td>
	<label>Horas Becario: </label>
	<input type="text" name="totalh" size="6"><br><br>
	 <input type="submit" name="reportetotal" value="Reporte Global">
	</td>
       </table>

 </form>

    <?php 
	
	
//------------------------------- REPORTE INDIVIDUAL ----------------------------------------------------------------------------------------------	
	if (isset($_POST['reporteid'])){

if(isset($_POST['colaboradores'])){
   
	$taux="";

  $query = "select id_colaborador,hora_entrada,hora_salida,horas_realizadas,fecha,desbloqueo,DAYNAME(fecha) from registros where id_colaborador='".$_POST['colaboradores']."' and fecha between '".$_POST['fechai']."' and '".$_POST['fechaf']."' UNION
  SELECT id_colaborador, c_entrada, c_salida, hora_e,fecha, observaciones, DAYNAME(fecha) FROM contingencia where id_colaborador='".$_POST['colaboradores']."' and fecha between '".$_POST['fechai']."' and '".$_POST['fechaf']."' ORDER BY fecha";
  $result=  mysqli_query($conexion,$query) or die ("No se permiten caracteres...!!!");  

echo "<table width=80%>";  
	
echo "<tr>";  
echo "<th>Id Colaborador</th>";  
echo "<th>Entrada</th>";  
echo "<th>Salida</th>"; 
echo "<th>Total</th>"; 
echo "<th>Contingencia</th>"; 
echo "<th>Fecha</th>";  
echo "<th>Dia</th>";
echo "</tr>";  
$horan="00:00:00"	;
while ($row = mysqli_fetch_row($result)){   
    echo "<tr>";  
    echo "<td>$row[0]</td>"; 
	if(strcmp($row[1], $horan)== 0) 
    {
	     echo "<td></td>";
		echo "<td></td>";
		echo "<td></td>";
	}else{
		echo "<td>$row[1]</td>";
		 echo "<td>$row[2]</td>"; 
     echo "<td>$row[3]</td>"; 
	}
	
   
	if(strcmp($row[5],0)== 0) 
    {
		echo "<td></td>";
	}else{
		if(strcmp($row[5],1)==0)
		{
			echo "<td> Desbloqueo...!!! </td>";
		}else{
            echo "<td>$row[5]</td>";
		}
	}	
	echo "<td>$row[4]</td>";
	echo "<td>$row[6]</td>";
    echo "</tr>";  
}  
echo "</table>";  
    
    
    $total = "select SEC_TO_TIME(SUM(TIME_TO_SEC(horas_realizadas))) from registros where id_colaborador='".$_POST['colaboradores']."' and fecha between '".$_POST['fechai']."' and '".$_POST['fechaf']."'";
   
    $result1=  mysqli_query($conexion,$total) or die ("No se permiten caracteres...!!!");  
	
	$total2 = "select SEC_TO_TIME(SUM(TIME_TO_SEC(hora_e))) from contingencia where id_colaborador='".$_POST['colaboradores']."' and fecha between '".$_POST['fechai']."' and '".$_POST['fechaf']."'";
   
    $result2=  mysqli_query($conexion,$total2) or die ("No se permiten caracteres...!!!"); 
	
	$total3="SELECT SEC_TO_TIME((select SUM(TIME_TO_SEC(horas_realizadas)) from registros where id_colaborador='".$_POST['colaboradores']."' and fecha between '".$_POST['fechai']."' and '".$_POST['fechaf']."')+(select SUM(TIME_TO_SEC(hora_e)) from contingencia where id_colaborador='".$_POST['colaboradores']."' and fecha between '".$_POST['fechai']."' and '".$_POST['fechaf']."'))";
	
	$result3=  mysqli_query($conexion,$total3) or die ("Error"); 

	$total4="Select SUM(desbloqueo) from registros WHERE id_colaborador='".$_POST['colaboradores']."' and fecha between '".$_POST['fechai']."' and '".$_POST['fechaf']."'";
	
	$result4= mysqli_query($conexion,$total4) or die ("Error");
	
	$taux="";
	$thoran="";
	
 
echo "<table >";  
echo "<tr>";  
echo '<th colspan="4">Total de Horas</th>';  
echo "</tr>";  
echo "<tr>";  
echo "<th>Total de Horas Servicio</th>";	
echo "<th>Contingencias</th>"; 
echo "<th>Desbloqueos</th>"; 
echo "<th>Total</th>"; 
echo "</tr>";
echo "<tr>"; 	
while ($row1 = mysqli_fetch_row($result1)){   
     
    echo "<td>$row1[0]</td>";  
	$thoran=$row1[0];
   
}
	while ($row2 = mysqli_fetch_row($result2)){   
     
    echo "<td>$row2[0]</td>";  
   
}
	while ($row4 = mysqli_fetch_row($result4)){
	$taux=$row4[0];     
    echo '<td> <span style="color:red">'.$row4[0].":00:00</span></td>";  
   
}
	while ($row3 = mysqli_fetch_row($result3))
	{
		if(is_null($row3[0])){
			echo "<td>";
		echo desbloqueo($taux,$thoran);
		echo "</td>";
			
		}else{
		echo "<td>";
		echo desbloqueo($taux,$row3[0]);
		echo "</td>";	     
		}
} 
	
	   echo "</tr>";
echo "</table>"; 
}
}


//-------------------------------- REPORTE TOTAL -------------------------------------------------------------------------------------------------------
if (isset($_POST['reportetotal']))
{
	
if(isset($_POST['fechai']))
{
	
if(isset($_POST['totalh']))
{
	$subtotal=$_POST['totalh'];
	$compara="";
	$totaux="";
	$taux="";
	
	echo "<table >";  
echo "<tr>";  
echo '<th colspan="7">Total de Horas</th>';  
echo "</tr>";  
echo "<tr>";  
echo "<th>ID</th>"; 
echo "<th>Nombre</th>"; 
echo "<th>Modalidad</th>"; 
echo "<th>Total de Horas Servicio</th>";	
echo "<th>Contingencias</th>"; 
echo "<th>Desbloqueos</th>"; 
echo "<th>Total</th>"; 
echo "</tr>";

	$ids= "SELECT id FROM colaboradores"; 
    $ids = mysqli_query($conexion,$ids) or die("error"); 
	while($row=mysqli_fetch_array($ids))
	{
		echo "<tr>"; 
		$reportetotal="SELECT id, nombre, modalidad from colaboradores WHERE id='".$row[0]."' ";
	    $reportestotal=mysqli_query($conexion,$reportetotal) or die ("error");
		while($row0=mysqli_fetch_array($reportestotal))
		{
			$compara=$row0[2];
		 echo "<td>".$row0[0]."</td>";
		 echo "<td>".$row0[1]."</td>";
		 echo "<td>".$row0[2]."</td>";
			
		}
		
		
	    $rt1="select SEC_TO_TIME(SUM(TIME_TO_SEC(horas_realizadas))) from registros where id_colaborador='".$row[0]."' and fecha between '".$_POST['fechai']."' and '".$_POST['fechaf']."'";
		$rt1 = mysqli_query($conexion,$rt1) or die("error");
		while($row1=mysqli_fetch_array($rt1))
		{
			echo "<td>".$row1[0]."</td>";
			$totaux=$row1[0];
		}
		
		$rt2 = "select SEC_TO_TIME(SUM(TIME_TO_SEC(hora_e))) from contingencia where id_colaborador='".$row[0]."' and fecha between '".$_POST['fechai']."' and '".$_POST['fechaf']."'";   
        $rt2=  mysqli_query($conexion,$rt2) or die ("No se permiten caracteres...!!!"); 
		while($row2=mysqli_fetch_array($rt2))
		{
		
		 echo "<td>".$row2[0]."</td>";
			
		}
		
		$rt4="Select SUM(desbloqueo) from registros WHERE id_colaborador='".$row[0]."' and fecha between '".$_POST['fechai']."' and '".$_POST['fechaf']."'";
	
		$rt4= mysqli_query($conexion,$rt4) or die ("Error");
		while($row4=mysqli_fetch_array($rt4))
		{
			$taux=$row4[0];
			if(strcmp($row4[0],0)==0)
			{
				echo "<td></td>";
			}else{		
		  		echo '<td> <span style="color:red">'.$row4[0].":00:00</span></td>";
			}
			
		}
	
		
	    $rt3="SELECT SEC_TO_TIME((select SUM(TIME_TO_SEC(horas_realizadas)) from registros where id_colaborador='".$row[0]."' and fecha between '".$_POST['fechai']."' and '".$_POST['fechaf']."')+(select SUM(TIME_TO_SEC(hora_e)) from contingencia where id_colaborador='".$row[0]."' and fecha between '".$_POST['fechai']."' and '".$_POST['fechaf']."'))";
		$rt3=  mysqli_query($conexion,$rt3) or die ("No se permiten caracteres...!!!"); 
		
		while($row3=mysqli_fetch_array($rt3))
		{
		
			if(empty($row3[0]))
			{
				echo "<td>";
					if (preg_match("/BECARIO/i", $compara))
		 			{
						$rth=desbloqueo($taux,$totaux);
		   				RestarHora($subtotal,$rth);
		   	   
	     			}else{
				
				 		echo desbloqueo($taux,$totaux);
			        }		
			echo "</td>";
				
			}else{
	     
		        echo "<td>";
				if (preg_match("/BECARIO/i", $compara))
		 		{
					echo $row3[0]."<br>";
					$rth=desbloqueo($taux,$row3[0]);
					RestarHora($subtotal,$rth);
					
		   	   
	     		}else{				
					 echo desbloqueo($taux,$row3[0]);
			     }		
				
				
				
				echo "</td>";
			}
		}
		
	
	echo "</tr>"; 
	
	}
	
	echo "</table>"; 

	
}
}
}
		

function RestarHora($horaini,$horafin)
{	
	$horas = preg_split("/:/", $horafin);	
    $horaux=$horas[0] - $horaini;
	$minaux=60-$horas[1];
	$segaux=60-$horas[2];
	if($horaux<0)
	{
		echo "[".$horaini.']> <span style="color:red">'.abs($horaux).":".$minaux.'</span>';
	}else{
		echo "[".$horaini.']> '.$horaux.":".$horas[1];
		
	}	
}
	
function desbloqueo($horaini,$horafin)
{	

	$horas = preg_split("/:/", $horafin);	
    $horaux=$horas[0] - $horaini;
	$minaux=$horas[1];
	$segaux=$horas[2];
	return $horaux.":".$minaux.":".$segaux;
	
}



  	
?> 
    
    

  </center>
    

 </body>
</html>
