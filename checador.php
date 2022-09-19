<?php 
//Selecccciona la zona horaria para que la fecha y la hora este correcta
date_default_timezone_set('America/Mexico_City');

//Se hace la conexion a la base de datos
$host_db = "localhost";
$user_db = "root";
$pass_db = "";
$db_name = "checadorus";
$tbl_name = "registros";
$conexion = new mysqli($host_db, $user_db, $pass_db, $db_name);

//se recogen las variables insertadas en el formulario Checador.html
$tipo=$_POST['tipo'];
$id=$_POST['id'];
 
//checa que la conexion se haya hecho perfectamente, si no manda errror
 if ($conexion->connect_error){
 die("La conexion falló: " . $conexion->connect_error);
 }

//primera validacion: Se verifica que el Id este registrado en la Base de Datos Colaboradores para poder continuar...
//esta instruccion se hace para cualquier seleccion ya sea Entrada,Salida o Permiso
$primer=mysqli_query($conexion,"select * from  checadorus.colaboradores where id='$id'") or die ("No se permiten caracteres...!!!"); 
if(mysqli_num_rows($primer)==0)
{ 
	//Si no se encotro el ID le manda la alerta de que que no existe y acaba el ciclo
	echo '<script language="javascript">alert("No existe registro de tu ID...!!!");</script>'; 			
}else{
	//Si se encontro el id entonces saca el valor del RadioButton y se dirige hacia el if que este correcto ya sea entrada, salida, permiso
	          
    //Si se selecciona Entrada entrara en este ciclo para registrar entrada
	if ($tipo==1)
    {   
		//Se verifica que el ID se completamente numerico para poder detectar el horario normal del colaborador
		if(is_numeric($id))
		{
			//si el id el complemtamente numerico entra en este ciclo
			$fechaactual = date("Y-m-d"); 
			//seguna validacion: esta validacion registra que el ID especificado no haya registrado entrada en el dia actual
			$validacion=mysqli_query($conexion,"select * from  registros where id_colaborador='$id' and fecha='$fechaactual'"); 
    		if(mysqli_num_rows($validacion)>0)
			{ 
				//si existe un registro del ID en la fecha actual, manda un script para avisar que ya esta registrado el dia de hoy
				echo '<script language="javascript">alert("Ya has registrado entrada el dia de hoy...!!!");</script>'; 
			}else{	
				//si no existe registro alguno en la tabla Registros entra a este ciclo 
				
				//se hace una consulta en la tabla horarios que trae los retardos y la hora de entrada 
				$retardosql= "select retardos,hora_entrada from horarios where id='$id'"; 
        		$retardosql = mysqli_query($conexion,$retardosql) or die("No se permiten caracteres...!!!"); ;         
        		$retardos="";  
        		$horario="";
        		$minutoAnadir=15; //minutos a añadir a la hora de entrada del colaborador para verificar retardos
        		$horareg= date("H:i:s"); //se extrae la hora actual del sistema y se guarda en una variable
        		while ($row=mysqli_fetch_assoc($retardosql)) 
        		{  
					//se extraen los campos de la base de datos, que estan especificados en la consulta anterior
           			$retardos = ($row['retardos']."");  
           			$horario= ($row['hora_entrada'].""); 
        		}         
					//se suman los minutos al horario de entrada de cada colaborador
            		$segundos_horaInicial=strtotime($horario);
        			$segundos_minutoAnadir=$minutoAnadir*60;
				 	//hora nueva ya sumados los minutos correspondientes para el retardo  en este caso son 15
        			$nuevaHora=date("H:i:s",$segundos_horaInicial+$segundos_minutoAnadir);
        			//hace la comparacion de la hora actual del sistema con la hora de entrada mas los 15 min añadidos de tolerancia de cada colaborador  
					if( $horareg > $nuevaHora ) 
        			{
						//si la hora actual es mayor a la hora de entrada +15min de tolerancia de cada colaborador 
						//le suma un retardo a la variable $retardos
            			$retardos=$retardos+1;
						//se le agrega la varible $retardos a la tabla horarios en el ID en cuestion
               			$acumular="UPDATE horarios SET retardos='$retardos' where id='$id'";
						//verifica que no tenga mas de dos retardos para poder registrar la hora de entrada
               			mysqli_query($conexion,$acumular) or die (mysql_error());
            			if($retardos>2)
            			{
							//si cuenta con mas de 2 retardos no le permite registrar entrada hasta que desbloquen su id, manda alerta para
							//que vayan con el coordinador a que les desbloquie su ID
               				echo '<script type="text/javascript">
               				alert("Tienes suficientes retardos como para que te regañen, ve con tu jefe inmediato para que desbloquie tu ID...!!!");
               				</script>';
            			} else{   
							//si cuenta menos de 2 retardos pero la hora actual sigue siendo mayor 
							//inserta la hora de entrada y la fecha correspondiente al id en cuestion
              				$query = "INSERT INTO registros (id_colaborador, hora_entrada, fecha) VALUES ('$id', now(), now())";   
               				mysqli_query($conexion,$query);
							//manda alerta para avisar que se paso de la hora de entrada, que si se registro la hora pero con retardo.
               				echo '<script language="javascript">alert("Te has pasado del tiempo... tu asistencia cuenta pero con retardo '.$horareg.'");</script>'; 
            			}
        			} else {
						if($retardos>2)
            			{
							//si cuenta con mas de 2 retardos no le permite registrar entrada hasta que desbloquen su id, manda alerta para
							//que vayan con el coordinador a que les desbloquie su ID
               				echo '<script type="text/javascript">
               				alert("Tienes suficientes retardos como para que te regañen, ve con tu jefe inmediato para que desbloquie tu ID...!!!");
               				</script>';
            			} else{   
						//si la hora actual es menor a la hora de entrada +15min se inserta el registro sin contar retardo
						//ya que entro en una hora adecuada
               			$query = "INSERT INTO registros (id_colaborador, hora_entrada, fecha) VALUES ('$id', now(), now())";   
               			mysqli_query($conexion,$query)  or die (mysql_error());                
               			echo '<script language="javascript">alert("Tu hora a sido registrada...!!!! '.$horareg.'");</script>';
						}
					}
			   
			} 
			//si el ID cuenta con una letra esto quiere decir que va a agarrar el horario secundario del colaborador
			//esto es solo si, el colaborador cuenta con diferentes horarios de entrada en la semana
		} else { 
			
 				$id = substr($id, 0,-1);//esta funcion le substrae la ultima letra asignada al ID para poder dejarlo totalmente numerico
			
				//de aqui para abajo hace todo el proceso descrito arriba solo que en vez de tomar el horario normal, toma el horario secundario del 
				//ID en cuestion
			
				$fechaactual = date("Y-m-d"); 
				$validacion=mysqli_query($conexion,"select * from  registros where id_colaborador='$id' and fecha='$fechaactual'"); 
    			if(mysqli_num_rows($validacion)>0)
				{ 
					echo '<script language="javascript">alert("Ya has registrado entrada el dia de hoy...!!!");</script>'; 
				}else{	
				$retardosql= "select retardos,entrada_secu from horarios where id='$id'"; 
				$retardosql = mysqli_query($conexion,$retardosql) or die("No se permiten caracteres...!!!"); ;         
				$retardos="";  
				$horario="";
				$minutoAnadir=15;
				$horareg= date("H:i:s");
				while ($row=mysqli_fetch_assoc($retardosql)) 
				{  
					$retardos = ($row['retardos']."");  
					$horario= ($row['entrada_secu'].""); 
				}         
				$segundos_horaInicial=strtotime($horario);
				$segundos_minutoAnadir=$minutoAnadir*60;
				$nuevaHora=date("H:i:s",$segundos_horaInicial+$segundos_minutoAnadir);  
				if( $horareg > $nuevaHora ) 
				{
					$retardos=$retardos+1;
					if($retardos>2)
					{
						echo '<script type="text/javascript">
						alert("Tienes suficientes retardos como para que te regañen, ve con tu jefe inmediato para que desbloquie tu ID...!!!");
						</script>';
					} else{              
						$query = "INSERT INTO registros (id_colaborador, hora_entrada, fecha) VALUES ('$id', now(), now())";   
						mysqli_query($conexion,$query);
						$acumular="UPDATE horarios SET retardos='$retardos' where id='$id'";
						mysqli_query($conexion,$acumular) or die (mysql_error());
						echo '<script language="javascript">alert("Te has pasado del tiempo... tu asistencia cuenta pero con retardo '.$horareg.'");</script>'; 
					}
				} else { 
					if($retardos>2)
            			{
							//si cuenta con mas de 2 retardos no le permite registrar entrada hasta que desbloquen su id, manda alerta para
							//que vayan con el coordinador a que les desbloquie su ID
               				echo '<script type="text/javascript">
               				alert("Tienes suficientes retardos como para que te regañen, ve con tu jefe inmediato para que desbloquie tu ID...!!!");
               				</script>';
            		} else{ 
					$query = "INSERT INTO registros (id_colaborador, hora_entrada, fecha) VALUES ('$id', now(), now())";   
					mysqli_query($conexion,$query)  or die (mysql_error());                
					echo '<script language="javascript">alert("Tu hora a sido registrada...!!!! '.$horareg.'");</script>';
					}
				}
			   
				} 
				
			}		
       	       
	}//se termina el ciclo de registro de entrada.
    

    //Si se eligio salida entra en este ciclo para registrar salida
    if ($tipo==2)
    {
       $fechaactual = date("Y-m-d"); //extrae fecha actual del sistema y lo guarda en la variable
        $horaactual= date("H:i:s"); //extrae hora actual del sistema y lo guarda en la variable
		
		//primera validacion: se valida que haya un registro con el Id en cuestion y la fache actual en la Tabla Registros
        $valisal=mysqli_query($conexion,"select * from  registros where id_colaborador='$id' and fecha='$fechaactual'"); 
    	if(mysqli_num_rows($valisal)>0)
		{
			//segunda validacion: se valida la hora_salida este en 00:00:00 esto es para que no chequen salida dos veces el mismo dia
			$valisal2=mysqli_query($conexion,"select * from  registros where id_colaborador='$id' and fecha='$fechaactual' and hora_salida='00:00:00'"); 
    		if(mysqli_num_rows($valisal2)>0)
			{
				//si cumple las dos validaciones anteriores, se hace una consulta para extraer la hora de entrada segun el id y la fecha actual
        		$query3= "select hora_entrada from registros where id_colaborador='$id' and fecha='$fechaactual'"; 
        		$resultado = mysqli_query($conexion,$query3); 
                $horaini="";  
        		while ($row_tb=mysqli_fetch_assoc($resultado)) 
        		{  
					//se extrae el campo Hora_entrada de la base de datos donde coincida el id y la fecha actual
            		$horaini = ($row_tb['hora_entrada']."");  
        		}     
				//se declara una variable de horas_realizadas para conocer cuanto tiempo fue el que estubo trabajando
				//se manda llamar la funcion RestarHoras() enviando la hora de entrada extraida anteriormente y la hora actual que es la hora de salida
        		$horasreali=RestarHoras($horaini,$horaactual);  
				//se hace la insercion de la hora de salida, y las horas realizadas en la tabla registros en el id yla fecha en cuestion
        		$query2 ="UPDATE registros SET hora_salida=now(), horas_realizadas='$horasreali' WHERE id_colaborador='$id' and fecha='$fechaactual'";   
        		mysqli_query($conexion,$query2) or die (mysql_error());
				//manda alerta de que la salida fue realizada con exito
        		echo '<script language="javascript">alert("Salida realizada con exito...!!! '.$horaactual.'");</script>';      
				mysqli_free_result($resultado); 
			}else{
				//no cumple la segunda validacion, entonces si la hora de salida ya esta actualizada manda alerta para avisarle que ya checo salida, ya no //puede checar dos veces
				 echo '<script language="javascript">alert("Ya checaste salida, ya no puedes checar otra vez..!!!");</script>'; 
			}
		}else{	
			//no cumpe la primera validacion, entonces quiere decir que todavia no checa entrada en la fecha actual y le manda alerta
        	echo '<script language="javascript">alert("Todavia no checas entrada el dia de HOY");</script>'; 
		}	
    }//fin del ciclo de registrar salida

    //Si se seleccion permiso entra en este ciclo para registrar permiso
    if ($tipo==3)
	{
		$fechaactual = date("Y-m-d"); 
		//validacion: se valida que no haya registro previo de entrada para el id especificado
		$validacionp=mysqli_query($conexion,"select * from  registros where id_colaborador='$id' and fecha='$fechaactual'"); 
    	if(mysqli_num_rows($validacionp)>0){ 
			//si ya hay registro previo manda alerta de que ya se ha registrado ya no cuenta el permiso
			echo '<script language="javascript">alert("Ya has registrado entrada el dia de hoy...!!!");</script>'; 
		}else{	
			//si no hay registro inserta el permiso agregando un 1 al campo permiso de la base datos para que no cuente horas ni nada y la fecha del permiso
        	$permiso = "INSERT INTO registros (id_colaborador, fecha, permiso) VALUES ('$id', now(), 1)";  
        	mysqli_query($conexion,$permiso) or die (mysql_error());
        	echo '<script language="javascript">alert("Permiso agregado con exito...!!!");</script>';
		}
    }
	
}

//Esta funcion es la que resta la hora de entrada y la hora de salida, para poder tener la horas realizadas 
//de tal dia, la cual retornara el total de horas para poder ingresarla a la base de datos.
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


mysqli_close($conexion);
include("checador.html");    
?>