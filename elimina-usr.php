<?php
session_start();
if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == true &&  $_SESSION['tipo']==1) {

} else {
	echo '<script language="javascript">alert("Debes iniciar sesion como Administrador...!!!");</script>'; 
   header("Location: login.php");

exit;
}

$host_db = "localhost";
 $user_db = "root";
 $pass_db = "";
 $db_name = "checadorus";
 $tbl_name = "colaboradores"; 


 $conexion = new mysqli($host_db, $user_db, $pass_db, $db_name);

 
 if ($conexion->connect_error) {
 die("La conexion falló: " . $conexion->connect_error);
 }
	 $elimina= "SELECT * FROM colaboradores"; 
     $elimina = mysqli_query($conexion,$elimina) or die("error"); 

?>
<html>
<head>
 <title>Eliminar Usuarios</title>
 <meta charset = "utf-8">
    <style>
    table { 
  width: 100%; 
  border-collapse: collapse; 
}
/* Zebra striping */
tr:nth-of-type(odd) { 
  background: #eee; 
}
th { 
  background: #333; 
  color: white; 
  font-weight: bold; 
}
td, th { 
  padding: 6px; 
  border: 1px solid #ccc; 
  text-align: left; 
}    
    </style>
</head>

<body style="background-color:#01A9DC;">
    
     <br><br>
<center>
<form method="post" action="elimina-usr.php">
<table >
<tr>
<th>Eliminar</th>
<th>ID</th>
<th>Nombre</th>
<th>Apellido Paterno</th>
<th>Apellido Materno</th>
<th>Carrera</th>
<th>Modalidad</th>
</tr>
   <?php
while($row=mysqli_fetch_array($elimina)){
    ?>
<tr>
   <td><input type="checkbox" name="casilla[]" value="<?php echo $row['id']; ?>"></td>   
<td><?php echo $row['id']; ?> </td>   
<td><?php echo $row['nombre'] ;?></td>
<td><?php echo $row['apepat'];?></td>
<td><?php echo $row['apemat'];?></td>
<td><?php echo $row['carrera'] ;?></td>
	<td><?php echo $row['modalidad'] ;?></td>
</tr>
<?php } ?>
</table>
	<br>
<input type="submit" value="Eliminar">
	
</form>
	</center>
</body>
</html>
<?php


   if (isset($_POST['casilla'])){


foreach ($_POST['casilla'] as $row['id']){

	$eliminar= "delete from colaboradores where id=".$row['id']." "; 
    $eliminar = mysqli_query($conexion,$eliminar) or die("One"); 
	
	$eliminarh= "delete from horarios where id=".$row['id']." "; 
    $eliminarh= mysqli_query($conexion,$eliminarh) or die("two");
	
	$eliminarr= "delete from registros where id_colaborador=".$row['id']." "; 
    $eliminarr= mysqli_query($conexion,$eliminarr) or die("three");

	$eliminarc= "delete from contingencia where id_colaborador=".$row['id']." "; 
    $eliminarc= mysqli_query($conexion,$eliminarc) or die("four");

}
   }
?>