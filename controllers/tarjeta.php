<?php

	if(isset($_POST)){
		include("../conexion.php");
		session_start();
		if(!isset($_SESSION['kodil']['id'])){
			echo "<script>window.location='login.php'</script>";
			return 0;
		}
		//Bloquear
		if($_GET['v']=='debloquear'){
			mysqli_query($conexion,"DROP EVENT IF EXISTS debito");
			do{
				$cvv=mt_rand(0,999);
			}while(strlen($cvv)<3);
			$sql_event="CREATE EVENT debito
    				ON SCHEDULE AT 
    					CURRENT_TIMESTAMP + INTERVAL 3 MINUTE
					DO
    				BEGIN
      					UPDATE ".$_POST['tarjeta']." SET bloqueada='0', cvv='-' WHERE account='".$_SESSION['kodil']['account']."';
      					DROP EVENT IF EXISTS debito;
					END";
			mysqli_query($conexion,$sql_event) or die ("error");
			mysqli_query($conexion,"SET GLOBAL event_scheduler='ON'") or die ("error");
			mysqli_query($conexion,"UPDATE ".$_POST['tarjeta']." SET bloqueada='1', cvv='".$cvv."' WHERE account='".$_SESSION['kodil']['account']."'") or die ("error");
			echo "<strong>Código de seguridad: </strong>".$cvv;
			return "EXITO";
		}

		//Cancelar
		if($_GET['v']=='cancelar'){
			if($_POST['status']=='cancelar'){
				mysqli_query($conexion,"UPDATE ".$_POST['tarjeta']." SET cancelada='1' WHERE account='".$_SESSION['kodil']['account']."'") or die ("error");
				cambio_debito($conexion);
				echo "cance";
				return "EXITO";
			}else{
				mysqli_query($conexion,"UPDATE ".$_POST['tarjeta']." SET cancelada='0' WHERE account='".$_SESSION['kodil']['account']."'") or die ("error");
				cambio_debito($conexion);
				echo "desca";
				return "EXITO";
			}
		}
	}else{
		return header("location:../index.php");
	}

	function cambio_debito($conexion){
		$rs_kodil_debito=mysqli_query($conexion,"SELECT * FROM debito WHERE account='".$_SESSION['kodil']['account']."'");
		while($row_kodil_debito=mysqli_fetch_array($rs_kodil_debito)){
			$_SESSION['kodil_debito']=array(
				'bloqueada'=>$row_kodil_debito['bloqueada'],
				'cancelada'=>$row_kodil_debito['cancelada']
			);
		}
	}

?>