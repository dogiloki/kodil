<?php

	if(isset($_POST)){
		include("../conexion.php");

		//Registrarse
		if($_GET['v']=='register'){
			$name=$_POST['name'];
			$surname=$_POST['surname'];
			$email=$_POST['email'];
			$birth=$_POST['birth'];
			$phone=$_POST['phone'];
			$sexo=$_POST['sexo'];
			$user=$_POST['user'];
			$password=$_POST['password'];
			$password_veri=$_POST['password_veri'];
			$account="";
			$debito="";
			$contador=0;
			for($a=1; $a<=16; $a++){
				$contador++;
				$account.=mt_rand(0,9);
				$debito.=mt_rand(0,9);
				if($contador==4){
					$contador=0;
					$account.=" ";
					$debito.=" ";
				}
			}
			do{
				$cvv=mt_rand(0,999);
			}while(strlen($cvv)<3);
			$account=substr($account,0,strlen($account)-3);
			$debito=substr($debito,0,strlen($debito)-1);
			$img="";
			if($name=='' || $surname=='' || $email=='' || $birth=='' || $phone=='' || $user=='' || $password=='' || $password_veri==''){
				echo "vacio";
				return "ERROR";
			}
			$rs_email=mysqli_query($conexion,"SELECT * FROM users WHERE email LIKE '%".$email."%'");
			while($row_email=mysqli_fetch_array($rs_email)){
				if($row_email['email']==$email){
					echo "email";
					return "ERROR";
				}
			}
			if(obtener_edad($birth)<18){
				echo "birth";
				return "ERROR";
			}
			if(is_numeric($phone)==false || strlen($phone)<=9 || strlen($phone)>=11){
				echo "phone";
				return "ERROR";
			}
			$rs_user=mysqli_query($conexion,"SELECT * FROM users WHERE user LIKE '%".$user."%'");
			while($row_user=mysqli_fetch_array($rs_user)){
				if($row_user['user']==$user){
					echo "user";
					return "ERROR";
				}
			}
			if($password!=$password_veri){
				echo "password";
				return "ERROR";
			}else{
				$password=password_hash($_POST['password'],PASSWORD_DEFAULT,array("cost"=>10));
			}
			if($sexo=="Hombre"){
				$img="img/hombre.png";
			}else{
				$img="img/mujer.png";
			}
			mysqli_query($conexion,"INSERT INTO users VALUES ('".uniqid()."','$name','$surname','$email','$birth','$phone','$sexo','$user','$password','$img','$account','0','1','0',now(),'1','0')") or die ('error');
			mysqli_query($conexion,"INSERT INTO debito VALUES ('".uniqid()."','$account','$debito','$cvv','01/25','0','0','9000','999999999')") or die ('error');
			mysqli_query($conexion,"INSERT INTO finanzas VALUES ('".uniqid()."','$account','0','0'") or die ('error');
			mysqli_query($conexion,"INSERT INTO movimientos VALUES ('".uniqid()."','Kodil','".$account."','cuenta','".utf8_decode('Bienvenido a Kodil')."','1500',now())") or die ("error");
			echo "registrado";
			return "EXISTO";
		}

		//Logiarse
		if($_GET['v']=='login'){
			if($_POST['user']=='' || $_POST['password']==''){
				echo "vacio";
				return "ERROR";
			}
			$rs=mysqli_query($conexion,"SELECT * FROM users WHERE user LIKE '%".$_POST['user']."%'");
			while($row=mysqli_fetch_array($rs)){
				if(password_verify($_POST['password'],$row['password']) && $row['user']==$_POST['user'] && $row['active']=='1'){
					session_start();
					$_SESSION['kodil']=array(
						'id'=>$row['id'],
						'name'=>$row['name'],
						'surname'=>$row['surname'],
						'email'=>$row['email'],
						'phone'=>$row['phone'],
						'sexo'=>$row['sexo'],
						'user'=>$row['user'],
						'img'=>$row['img'],
						'account'=>$row['account'],
						'tarjeta_credito'=>$row['tarjeta_credito'],
						'tarjeta_debito'=>$row['tarjeta_debito'],
						'saldo'=>$row['saldo'],
						'active'=>$row['active'],
						'admin'=>$row['admin'],
						'fullname'=>$row['name']." ".$row['surname']
					);
					if(isset($_GET['apk'])){
						if($_GET['apk']=='true'){
							echo $_SESSION['kodil']['id'];
							return 0;
						}
					}
					echo "login";
					return "EXISTO";
				}
			}
			echo "error";
			return "ERROR";
		}
		//APK
		if($_GET['v']=='account'){
			$rs_account=mysqli_query($conexion,"SELECT * FROM users WHERE id='".$_POST['id']."'");
			while($row_account=mysqli_fetch_array($rs_account)){
				echo $row_account['account'];
				return 0;
			}
		}

		//Update user
		if($_GET['v']=='update-user'){
			if($_POST['user']==''){
				echo "Pon un usuario";
				return "ERROR";
			}
			if($_POST['password']==''){
				echo "Pon tú contraseña actual";
				return "ERROR";
			}else{
				session_start();
				$rs_pass=mysqli_query($conexion,"SELECT * FROM users WHERE id='".$_SESSION['kodil']['id']."'");
				while($row_pass=mysqli_fetch_array($rs_pass)){
					if(password_verify($_POST['password'],$row_pass['password'])){
						if($_POST['password-new']!=''){
							$password_new=password_hash($_POST['password-new'],PASSWORD_DEFAULT,array("cost"=>10));
							mysqli_query($conexion,"UPDATE users SET password='".$password_new."' WHERE id='".$_SESSION['kodil']['id']."'");
						}
						if(isset($_FILES['file']['name'])){
							move_uploaded_file($_FILES['file']['tmp_name'],"../upload_user/".$_SESSION['kodil']['id'].$_FILES['file']['name']) or die ("Error al subir imagen");
							if($_SESSION['kodil']['img']!='../img/hombre.png' || $_SESSION['kodil']['img']!='img/mujer.png'){
								unlink("../".$_SESSION['kodil']['img']);
							}
							mysqli_query($conexion,"UPDATE users SET img='upload_user/".$_SESSION['kodil']['id'].$_FILES['file']['name']."' WHERE id='".$_SESSION['kodil']['id']."'");
							$_SESSION['kodil']['img']=$row_pass['img'];
						}
						mysqli_query($conexion,"UPDATE users SET user='".$_POST['user']."' WHERE id='".$_SESSION['kodil']['id']."'");
						session_destroy();
						echo "update";
						return "EXISTO";
					}else{
						echo "Contraseña incorrecta";
						return "ERROR";
					}
				}
			}
		}
	}else{
		return header("location:../index.php");
	}

	/* Funciones */
		function obtener_edad($birth){
			$dia=date("j");
			$mes=date("n");
			$ano=date("Y");
			//descomponer fecha de nacimiento
			$ano_nac=substr($birth,0,4);
			$mes_nac=substr($birth,5,2);
			$dia_nac=substr($birth,8,2);
			if($mes_nac>$mes){
				$edad=$ano-$ano_nac-1;
			}else{
				if($mes==$mes_nac AND $dia_nac>$dia){
					$edad=$ano-$ano_nac-1;  
				}else{
					$edad=$ano-$ano_nac;
				}
			}
			return $edad;
		}

?>