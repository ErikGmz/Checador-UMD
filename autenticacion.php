<?php
session_start();
?>

<?php

$host_db = "localhost";
$user_db = "root";
$pass_db = "";
$db_name = "checadorus";

$conexion = new mysqli($host_db, $user_db, $pass_db, $db_name);

if ($conexion->connect_error) {
 die("La conexion falló: " . $conexion->connect_error);
}

$username = $_POST['username'];
$password = md5($_POST['password']);
 
$sql = "SELECT * FROM checadorus.coordinadores WHERE id_coordinador = '$username'";

$result = mysqli_query($conexion,$sql);
$pass="";
$tipo="";
while ($row=mysqli_fetch_assoc($result)) 
{  
        $pass = ($row['pass'].""); 
		$tipo = $row['tipo'];
            
}    


 if (strcmp($password, $pass) == 0) 
{
	if($tipo==1){
 
    $_SESSION['loggedin'] = true;
    $_SESSION['username'] = $username;
	$_SESSION['tipo'] = $tipo;
	header("Location: frames.html");
		
	}else{
		$_SESSION['loggedin'] = true;
    	$_SESSION['username'] = $username;
		$_SESSION['tipo'] = $tipo;
		header("Location: contingencias.php");
	}
	
 } else { 
   include("login.php");
	echo '<script language="javascript">alert("El usuario o la clave son incorrectos...!!!");</script>'; 
 }
 mysqli_close($conexion); 
 ?>