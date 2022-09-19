<html lang="en">

<head>
 <title>Login</title>
 <meta charset = "utf-8">
	    <style type="text/css">

#registro {
    width:800px;
    padding:0px;
    margin:80px;
    border: 3px dashed #FFF;
    background-color:#01A9DC;
	float: right;

}

#registro fieldset {
    border:0;
	background-color: rgba(0,0,255,0);
    padding:50px 20px;
}

#registro legend {
    font: bold 30px Arial, sans-serif;
    color:white;
    padding:0 5px;

}

#registro p label {
    font:bold 18px Geneva, Arial, Helvetica, sans-serif;
    float: center;
    width: 22%;
    text-align:center;
    padding:5px;
    color: #fff;

}
		.mi-imagen-abajo-derecha{
    position:absolute;
    bottom:5px;
    right:10px;
}		

#registro p br { clear: left; }


        #fondo
{position: absolute; top: 0; left: 0; width: 100%; z-index: -1}
</style>
    
</head>

<body style="overflow-y:hidden">
	<img id="fondo" src="img/checador_izquierda.png" alt="background image" />

    
	  
    <center>
        <br><br><br><br>
    <div id="registro">

        <fieldset>
             <form action="autenticacion.php" method="post" >
            <legend>Login de Coordinadores</legend>
          
    
   
  <p>
        <label>ID COORDINADOR:</label><br>
        <input name="username" type="text" id="username" required>
        <br><br>

<label>PASSWORD:</label><br>
<input name="password" type="password" id="password" required>
<br><br>

<input id="enviar" type="submit" name="Submit" value="LOGIN">
</p>
</form>         
        
</fieldset>
        </div>

      
    </center>
</body>
</html>