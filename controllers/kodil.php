<?php

	if(isset($_POST['v'])){
		include("../conexion.php");
		if($_POST['v']=='comprar' && isset($_POST['cuenta_para'])){
			$datos=false;
			//$titular=$_POST['titular'];
			$num_tarjeta=$_POST['num_tarjeta'];
			$dia=str_replace(' ','',$_POST['dia']);
			$mes=str_replace(' ','',$_POST['mes']);
			if(strlen($dia)==1){
				$dia="0".$dia;
			}
			if(strlen($mes)==1){
				$mes="0".$mes;
			}
			$cvv=str_replace(' ','',$_POST['cvv']);
			$cantidad=$_POST['cantidad'];
			if($num_tarjeta=='' || $dia=='' || $mes=='' || $cvv=='' || $cantidad=='' || $num_tarjeta=='-'){
				echo "DATOS INCORRECTO EN MÉTODO DE PAGO";
				return 0;
			}
			$rs=mysqli_query($conexion,"SELECT * FROM debito WHERE bloqueada='1' AND cancelada='0' AND cvv!='-' AND vencimiento!='-'") or die ("ERROR EN SERVIDOR");
			$encontrado=false;
			while($row=mysqli_fetch_array($rs)){
				if($encontrado==false){
					if(str_replace(' ','',$num_tarjeta)==str_replace(' ','',$row['digitos']) && $row['vencimiento']==($dia."/".$mes) && $row['cvv']==$cvv){
						$encontrado=true;
						$rs_user=mysqli_query($conexion,"SELECT * FROM users WHERE account='".$row['account']."'") or die ("ERROR EN SERVIDOR");
						while($row_user=mysqli_fetch_array($rs_user)){
							if($row_user['saldo']>$cantidad){
								$rs_para=mysqli_query($conexion,"SELECT * FROM debito WHERE account='".$_POST['cuenta_para']."' AND cancelada='0'") or die ("ERROR AL TRANSFERIR");
								if(mysqli_num_rows($rs_para)<=0){
									echo "ERROR AL TRANSFERIR";
									return 0;
								}
								while($row_para=mysqli_fetch_array($rs_para)){
									mysqli_query($conexion,"CALL enviar('','".$row_user['account']."','".$row_para['account']."',".$cantidad.")") or die ('ERROR EN SERVIDOR');
									mysqli_query($conexion,"INSERT INTO movimientos VALUES ('".uniqid()."','".$row_user['account']."','".$row_para['account']."','debito','".utf8_decode('Pago en tienda virtual GOV')."','".$cantidad."',now())") or die ("ERROR EN SERVIDOR");
									echo "COMPRA CORRECTA";
									return 0;
								}
							}else{
								echo "SALDO INSUFICIENTE";
								return 0;
							}
						}
					}
				}
			}
			if($encontrado==false){
				echo "DATOS INCORRECTO EN MÉTODO DE PAGO";
				return 0;
			}
		}
	}

?>