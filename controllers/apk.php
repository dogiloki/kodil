<?php

	include("../conexion.php");
	if($_GET['tabla']=='user'){
		$rs_user=mysqli_query($conexion,"SELECT * FROM users WHERE id='".$_POST['id']."'");
		while($row_user=mysqli_fetch_array($rs_user)){
			if($_GET['v']=='fullname'){
				echo "<span style='white-space: nowrap;'>".$row_user['name']." ".$row_user['surname']."</span>";
				return 0;
			}
			if($_GET['v']=='img'){
				echo $row_user['img'];
				return 0;
			}
			if($_GET['v']=='saldo'){
				echo number_format($row_user['saldo']);
				return 0;
			}
			if($_GET['v']=='credito_digitos'){
				if($row_user['tarjeta_credito']=='1'){
					$rs_credito=mysqli_query($conexion,"SELECT * FROM credito WHERE account='".$row_user['account']."'");
					while($row_credito=mysqli_fetch_array($rs_credito)){
						echo $row_credito['digitos'];
						return 0;
					}
				}else{
					echo "SOLICITAR UNA TARJETA";
					return 0;
				}
			}
		}
	}
	if($_GET['tabla']=='debito'){
		$rs_debito=mysqli_query($conexion,"SELECT * FROM debito WHERE account='".$_POST['account']."'");
		while($row_debito=mysqli_fetch_array($rs_debito)){
			if($_GET['v']=='debito_digitos'){
				echo $row_debito['digitos'];
				return 0;	
			}
		}
	}
	if($_GET['tabla']=='movimientos'){
		$sql_movimientos="";
		if($_GET['realizado']=='todos'){
			$sql_movimientos="SELECT * FROM movimientos WHERE desde='".$_POST['account']."' OR para='".$_POST['account']."' ORDER BY date DESC";
		}else{
			$sql_movimientos="SELECT * FROM movimientos WHERE realizado='".$_GET['realizado']."' AND (desde='".$_POST['account']."' OR para='".$_POST['account']."') ORDER BY date DESC";
		}
		$rs_movimientos=mysqli_query($conexion,$sql_movimientos);
		while($row_movimientos=mysqli_fetch_array($rs_movimientos)){
			if($_GET['v']=='tipo_operacion'){
				echo $row_movimientos['tipo_operacion']." - ".$row_movimientos['date'].",";
			}
			if($_GET['v']=='id'){
				echo $row_movimientos['id'].",";
			}
		}
		return 0;
	}
	if($_GET['tabla']=='movimientos_datos'){
		$rs_movi_datos=mysqli_query($conexion,"SELECT * FROM movimientos WHERE id='".$_POST['id']."'");
		while($row_movi_datos=mysqli_fetch_array($rs_movi_datos)){
			echo "<strong>Tipo de operación</strong><br>".$row_movi_datos['tipo_operacion']."<br><br>";
			echo "<strong>Desde</strong><br>".$row_movi_datos['desde']."<br><br>";
			echo "<strong>Para</strong><br>".$row_movi_datos['para']."<br><br>";
			if($row_movi_datos['para']==$_POST['account']){
				echo "<strong>Cantidad</strong><br><span style='color: green'>+ ".$row_movi_datos['cantidad']."</span><br><br>";
			}else{
				echo "<strong>Cantidad</strong><br><span style='color: red'>- ".$row_movi_datos['cantidad']."</span><br><br>";
			}
			echo "<strong>Fecha</strong><br>".$row_movi_datos['date']."<br><br>";
			return 0;
		}
	}
	if($_GET['tabla']=='finanzas'){
		$rs_finanzas=mysqli_query($conexion,"SELECT * FROM finanzas WHERE account='".$_POST['account']."'");
		while($row_finanzas=mysqli_fetch_array($rs_finanzas)){
			echo "<strong style='color: green;'>INGRESOS: +".number_format($row_finanzas['ingresos'])."</strong><br><br><strong style='color: red;'>GASTOS: -".number_format($row_finanzas['gastos'])."</strong><br>";
			return 0;
		}
	}

	//Cuenta
	if($_GET['tabla']=='cuenta'){
		//Enviar dinero
		if($_GET['v']=='enviar'){
			if($_POST['cantidad']=='' || $_POST['password']==''){
				echo "vacio";
				return "ERROR";
			}
			if($_POST['cuenta']==''){
				$account="000";
			}else{
				$cuenta_existe=false;
				$rs_cuenta=mysqli_query($conexion,"SELECT * FROM users WHERE account='".$_POST['cuenta']."'");
				while($row_cuenta=mysqli_fetch_array($rs_cuenta)){
					$cuenta_existe=true;
				}
				if($cuenta_existe==false){
					echo "cuenta";
					return "ERROR";
				}
				$account=$_POST['cuenta'];
			}
			$rs_user=mysqli_query($conexion,"SELECT * FROM users WHERE id='".$_POST['id']."'");
			while($row_user=mysqli_fetch_array($rs_user)){
				if($row_user['saldo']<$_POST['cantidad'] || $row_user['saldo']<=0){
					echo "cantidad";
					return "ERROR";
				}
				if(!password_verify($_POST['password'],$row_user['password'])){
					echo "password";
					return "ERROR";
				}
			}
			if($_GET['qr']=='si'){
				require('../librerias/phpqrcode/qrlib.php');
    			$ruta="../codes/";
    			$id=uniqid();
    			$code=uniqid();
    			$nombre=$id.'.png';
    			$texto="CALL enviar(&".$id."&,&".$_POST['account']."&,&".$account."&,".$_POST['cantidad'].");INSERT INTO movimientos VALUES (&$code&,&".$_POST['account']."&,&".$account."&,&cuenta&,&".utf8_decode('Transferencia por QR desde otra cuenta')."&,&".$_POST['cantidad']."&,now())";
    			mysqli_query($conexion,"INSERT INTO codes_qr VALUES ('".$id."','".$_POST['account']."','".$account."','".$_POST['cantidad']."','".$code."','".$texto."',now(),'1','')") or die ("error");
    			QRcode::png($code,$ruta.$nombre,'H',7);
    			echo $nombre;
    			return "EXITO";
			}else{
				mysqli_query($conexion,"CALL enviar('','".$_POST['account']."','".$account."',".$_POST['cantidad'].")") or die ('error');
				mysqli_query($conexion,"INSERT INTO movimientos VALUES ('".uniqid()."','".$_POST['account']."','".$account."','cuenta','".utf8_decode('Transferencia directa desde otra cuenta')."','".$_POST['cantidad']."',now())") or die ("error");
				echo "enviado";
				return "EXISTO";
			}
		}
		if($_GET['v']=='recibir'){
			$texto=$_POST['file'];
			$rs_qr=mysqli_query($conexion,"SELECT * FROM codes_qr WHERE code='".$texto."'");
			while($row_qr=mysqli_fetch_array($rs_qr)){
				if($row_qr['active']=='0'){
					echo "cobrado";
					return "ERROR";
				}else{
					if($row_qr['desde']==$_POST['account']){
						echo "misma_cuenta";
						return "ERROR";
					}
					if($row_qr['para']!=$_POST['account']){
						if($row_qr['para']=='000'){

						}else{
							echo "no_es_tuyo";
							return "ERROR";
						}
						
					}
					$datos=str_ireplace("&","'",$row_qr['texto']);
					$datos=str_ireplace("000",$_POST['account'],$datos);
					$sql=explode(";",$datos);
					mysqli_query($conexion,$sql[0]) or die ("error");
					mysqli_query($conexion,$sql[1]) or die ("error");
					$rs_info=mysqli_query($conexion,"SELECT * FROM movimientos WHERE id='".$texto."'");
					while($row_info=mysqli_fetch_array($rs_info)){
						echo "<p><strong>Desde: </strong>".$row_info['desde']."</p>
							<p><strong>Para: </strong>".$row_info['para']."</p>
							<p><strong>Cantidad: </strong>".$row_info['cantidad']."</p>
							<p><strong>Fecha de cobro: </strong>".$row_info['date']."</p>";
					}
					return 0;
				}
			}
		}
	}

?>