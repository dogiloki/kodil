<?php

	if(isset($_POST)){
		include("../conexion.php");
		session_start();

		//Enviar dinero
		if($_GET['v']=='enviar'){
			if($_POST['cantidad']=='' || $_POST['password']==''){
				echo "vacio";
				return "ERROR";
			}
			if($_POST['cuenta']==''){
				$account="null";
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
			$rs_user=mysqli_query($conexion,"SELECT * FROM users WHERE id='".$_SESSION['kodil']['id']."'");
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
    			$texto="CALL enviar(&".$id."&,&".$_SESSION['kodil']['account']."&,&".$account."&,".$_POST['cantidad'].");INSERT INTO movimientos VALUES (&$code&,&".$_SESSION['kodil']['account']."&,&".$account."&,&cuenta&,&".utf8_decode('Transferencia por QR desde otra cuenta')."&,&".$_POST['cantidad']."&,now())";
    			mysqli_query($conexion,"INSERT INTO codes_qr VALUES ('".$id."','".$_SESSION['kodil']['account']."','".$account."','".$_POST['cantidad']."','".$code."','".$texto."',now(),'1','')") or die ("error");
    			$_SESSION['kodil']['qr']="codes/".$nombre;
    			QRcode::png($code,$ruta.$nombre,'H',7);
    			echo "qr";
    			return "EXITO";
			}else{
				mysqli_query($conexion,"CALL enviar('','".$_SESSION['kodil']['account']."','".$account."',".$_POST['cantidad'].")") or die ('error');
				mysqli_query($conexion,"INSERT INTO movimientos VALUES ('".uniqid()."','".$_SESSION['kodil']['account']."','".$account."','cuenta','".utf8_decode('Transferencia directa desde otra cuenta')."','".$_POST['cantidad']."',now())") or die ("error");
				echo "enviado";
				return "EXISTO";
			}
		}

		//Recibir
		if($_GET['v']=='recibir'){
			if($_POST['password']=='' || $_FILES['file']['name']==''){
				echo "vacio";
				return "ERROR";
			}
			$rs_user_qr=mysqli_query($conexion,"SELECT * FROM users WHERE id='".$_SESSION['kodil']['id']."'");
			while($row_user_qr=mysqli_fetch_array($rs_user_qr)){
				if(!password_verify($_POST['password'],$row_user_qr['password'])){
					echo "password";
					return "ERROR";
				}
			}
			if($_FILES["file"]["type"]!="image/png"){
				echo "no_valido";
				return "ERROR";
			}
			move_uploaded_file($_FILES['file']['tmp_name'],"../temp/".$_FILES['file']['name']) or die ('error');
			require('../librerias/phpqrdecode/autoload.php');
			$qrcode=new Zxing\QrReader("../temp/".$_FILES['file']['name']) or die ("error");
			$texto=$qrcode->text();
			$rs_qr=mysqli_query($conexion,"SELECT * FROM codes_qr WHERE code='".$texto."'");
			while($row_qr=mysqli_fetch_array($rs_qr)){
				if($row_qr['active']=='0'){
					echo "cobrado";
					return "ERROR";
				}else{
					if($row_qr['desde']==$_SESSION['kodil']['account']){
						echo "misma_cuenta";
						return "ERROR";
					}
					if($row_qr['para']!=$_SESSION['kodil']['account']){
						if($row_qr['para']=='null'){

						}else{
							echo "no_es_tuyo";
							return "ERROR";
						}
						
					}
					$datos=str_ireplace("&","'",$row_qr['texto']);
					$datos=str_ireplace("null",$_SESSION['kodil']['account'],$datos);
					$sql=explode(";",$datos);
					mysqli_query($conexion,$sql[0]) or die ("error");
					mysqli_query($conexion,$sql[1]) or die ("error");
					$_SESSION['kodil']['recibido']=$texto;
					unlink("../temp/".$_FILES['file']['name']);
					echo "recibido";
					return "EXISTO";
				}
			}
			echo "transferencia";
			return "ERROR";
		}

	}

	//Actualizar saldo
	if($_GET['v']=='saldo'){
		$rs_saldo=mysqli_query($conexion,"SELECT * FROM users WHERE account='".$_SESSION['kodil']['account']."'");
		while($row_Saldo=mysqli_fetch_array($rs_saldo)){
			$_SESSION['kodil']['saldo']=$row_Saldo['saldo'];
			echo "$".number_format($row_Saldo['saldo']);
		}
	}
	//Mostrar QR
	if($_GET['v']=='show-qr'){
		echo "<a href='".$_SESSION['kodil']['qr']."' download='Kodil-QR'><img src='".$_SESSION['kodil']['qr']."'></a>";
		return "EXISTO";
	}
	//Mostrar INFO TRANSFERENCIA
	if($_GET['v']=='show-info'){
		$rs_info=mysqli_query($conexion,"SELECT * FROM movimientos WHERE id='".$_SESSION['kodil']['recibido']."'");
		while($row_info=mysqli_fetch_array($rs_info)){
			echo "<p><strong>Desde: </strong>".$row_info['desde']."</p>
				<p><strong>Para: </strong>".$row_info['para']."</p>
				<p><strong>Cantidad: </strong>".$row_info['cantidad']."</p>
				<p><strong>Fecha de cobro: </strong>".$row_info['date']."</p>";
		}
		return "EXISTO";
	}


?>