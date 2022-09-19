<?php
session_start();
if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == true &&  $_SESSION['tipo']==1) {

} else {
	echo '<script language="javascript">alert("Debes iniciar sesion como Administrador...!!!");</script>'; 
   header("Location: login.php");

exit;
}
 

date_default_timezone_set('America/Mexico_City');
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
 <title>Desbloquear ID</title>
 <meta charset = "utf-8">
    <style>
    table { 
  width: 60%; 
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
     #fondo
{position: absolute; top: 0; left: 0; width: 100%; z-index: -1}
    </style>
</head>

		
<body style="overflow-y:hidden">
    <img id="fondo" src="img/Checador_centro.png" alt="background image" />

    
    <br><br><br>
<center>
 <form action="desbloquearid.php" method="post"> 
<table >
     <td>
 <label for="nombre">Id. Colaborador: </label>
 <input type="text" name="id" maxlength="32" required>
    </td>

<td>
 <input type="submit" name="submit" value="Desbloquear">
    </td>
       </table>

 </form>

    <?php  

if(isset($_POST['id'])){
    
    
$veri="select * from colaboradores where id=".$_POST['id'];  
$verificar=mysqli_query($conexion,$veri) or die ("No se permiten caracteres...!!!");  
if (mysqli_num_rows($verificar) == 0)  
{ 
    
echo '<script language="javascript">alert("No se encontro ID en la base de Datos...!!!");</script>';

}else{
   
        $retardodes= "select retardos from horarios where id='".$_POST['id']."'"; 
        $retardod = mysqli_query($conexion,$retardodes) or die("No se permiten caracteres...!!!"); ;         
        $retardos=""; 
        $horareg= date("H:i:s");
        while ($row=mysqli_fetch_assoc($retardod)) 
        {  
            $retardos = ($row['retardos']."");  
            
        }         
        
        $retardos=0;
        $des="UPDATE horarios SET retardos='$retardos' where id='".$_POST['id']."'";
        mysqli_query($conexion,$des) or die ("No se permiten caracteres...!!!");
    
       $reg = "INSERT INTO registros (id_colaborador, hora_entrada, fecha,desbloqueo) VALUES ('".$_POST['id']."', now(), now(), 1)";   
        mysqli_query($conexion,$reg) or die ("No se permiten caracteres...!!!");
        echo '<script language="javascript">alert("Se ha desbloqueado correctamente..!!! Tu hora de entrada es: '.$horareg.'");</script>'; 
                  
 
    
}

}
?>  
    
    

  </center>
    

 </body>
</html>