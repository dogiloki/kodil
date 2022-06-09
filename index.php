<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="author" content="Julio César Villanueva Ontiveros">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>KODIL</title>
	<link rel="stylesheet" type="text/css" href="css/header.css">
	<link rel="stylesheet" type="text/css" href="css/inicio.css">
	<script type="text/javascript" src="js/app.js"></script>
</head>
<body>

<?php

include("conexion.php");
session_start();
if(!isset($_SESSION['kodil']['id'])){
	$version="";
	$fp=fopen("apk/version.txt","r");
	while(!feof($fp)){
    	$version=fgets($fp);
	}
	fclose($fp);
	?>
	<header>
		<img src="img/logo.png" width="180px" height="40px" class="logo" onclick="window.location='index.php'">
		<section class="header">
			<li onclick="window.location='apk/kodil-<?php echo $version; ?>.apk'"><img src="img/apk.png" width="20px" height="20px" class="icon">Descarga la app</li>
			<li onclick="window.location='login.php'"><img src="img/ingresar.png" width="20px" height="20px" class="icon">Ingresar</li>
		</section>
	</header>
	<center class="banner"><img src="img/logo_letra.png" width="100%" height="100%" style="max-width: 1200px; min-width: 860px; max-height: 85vh;"></center>
	<?php
}else{
	?>
	<header>
		<img src="img/logo.png" width="180px" height="40px" class="logo" onclick="window.location='index.php'">
		<section class="header">
			<?php
				$rs_saldo=mysqli_query($conexion,"SELECT * FROM users WHERE account='".$_SESSION['kodil']['account']."'");
				while($row_Saldo=mysqli_fetch_array($rs_saldo)){
					echo "<li onclick='saldo()' id='saldo' title='Actualizar'>$".number_format($row_Saldo['saldo'])."</li>";
				}
			?>
			<li onclick="modal('menu')" style="min-width: 200px; max-width: 250px; white-space:nowrap; height: 40px; overflow: hidden;">
				<img src="<?php echo $_SESSION['kodil']['img']; ?>" width="30px" height="30px" style="border-radius: 100%;" class="icon"><?php echo $_SESSION['kodil']['fullname']; ?>
			</li>
			<li onclick="window.location='perfil.php'"><img src="img/perfil.png" width="20px" height="20px" class="icon">Perfil</li>
			<li onclick="window.location='tarjeta.php'"><img src="img/tarjeta.png" width="20px" height="20px" class="icon">Tarjeta</li>
			<li onclick="window.location='close.php'"><img src="img/salir.png" width="20px" height="20px" class="icon">Salir</li>
		</section>
		<section class="menu" id="menu" onclick="modal('menu')" style="display: none;">
			<div class="content-menu" onclick="modal('menu')">
				<div class="content-img"><img src="<?php echo $_SESSION['kodil']['img'] ?>" width='140px' height='140px'></div>
				<div class="content-name"><?php echo $_SESSION['kodil']['fullname'] ?></div>
				<div class="content-email"><?php echo $_SESSION['kodil']['email'] ?></div>
				<br><hr>
				<nav>
					<li onclick="window.location='estado_cuenta.php'">Estado de cuenta</li>
					<li onclick="window.location='enviar_recibir.php'">Envíar y recibir dinero</li>
					<li onclick="window.location='finanzas.php'">Finanzas</li>
					<?php
					if($_SESSION['kodil']['admin']=='1'){
						?>
						<li onclick="window.location='panel/index.php'">Página de administrador</li>
						<?php
					}
					?>
				</nav>
			</div>
		</section>
	</header>

	<section class="content">
		<div class="option" onclick="window.location='estado_cuenta.php'">
			<div class="content-img"><img src="img/estado_cuenta.png" width="100%" height="100%"></div>
			<div class="texto">ESTADO DE CUENTA</div>
		</div>
		<div class="option" onclick="window.location='enviar_recibir.php'">
			<div class="content-img"><img src="img/enviar_recibir.png" width="100%" height="100%"></div>
			<div class="texto">ENVÍAR O RECIBIR DINERO</div>
		</div>
		<div class="option" onclick="window.location='finanzas.php'">
			<div class="content-img"><img src="img/finanzas.png" width="100%" height="100%"></div>
			<div class="texto">FINANZAS</div>
		</div>
	</section>
	<?php
}

?>

</body>
</html>