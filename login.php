<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="author" content="Julio César Villanueva Ontiveros">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>KODIL</title>
	<link rel="stylesheet" type="text/css" href="css/header.css">
	<link rel="stylesheet" type="text/css" href="css/login.css">
	<script type="text/javascript" src="js/login.js"></script>
	<script type="text/javascript" src="js/app.js"></script>
</head>
<body>

	<?php
		session_start();
		if(isset($_SESSION['kodil']['id'])){
			return header("location:index.php");
		}
		$version="";
		$fp=fopen("apk/version.txt", "r");
		while(!feof($fp)){
    		$version=fgets($fp);
		}
		fclose($fp);
	?>

	<header>
		<img src="img/logo.png" width="180px" height="40px" class="logo" onclick="window.location='index.php'">
		<section class="header">
			<li onclick="window.location='apk/kodil-<?php echo $version; ?>.apk'"><img src="img/apk.png" width="20px" height="20px" class="icon">Descarga la app</li>
			<li onclick="window.location='login.php'" style="opacity: 0.8;"><img src="img/ingresar.png" width="20px" height="20px" class="icon">Ingresar</li>
		</section>
	</header>

	<center class="banner"><img src="img/logo_letra.png" width="100%" height="100%" style="max-width: 1200px; min-width: 860px; max-height: 85vh; opacity: 0.8;"></center>

	<center>
		<section class="login">
			<div class="title">INGRESAR</div>
			<input type="text" onkeyup="login2()" id="user" placeholder="Usuario" class="caja" autocomplete="off">
			<input type="password" onkeyup="login2()" id="password" placeholder="Contraseña" class="caja" autocomplete="off">
			<div class='aviso' id='aviso'></div>
			<input type="submit" onclick="login()" value="INGRESAR" class="btn">
			<a href="" class="olvidado">Olvide mi contraseña</a>
		</section>
	</center>

</body>
</html>