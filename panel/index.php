<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="author" content="Julio César Villanueva Ontiveros">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>PANEL KODIL</title>
	<link rel="stylesheet" type="text/css" href="css/header.css">
	<script type="text/javascript" src="js/app.js"></script>
</head>
<body>

<?php

include("../conexion.php");
session_start();
if(!isset($_SESSION['panel-kodil']) || $_SESSION['panel-kodil']!='true'){
	header("location=../index.php");
}else{
	?>
	<header>
		<img src="img/logo.png" width="180px" height="40px" class="logo" onclick="window.location='index.php'">
		<section class="header">
			<li onclick="modal('menu')" style="min-width: 200px; max-width: 250px; white-space:nowrap; height: 40px; overflow: hidden;">
				<?php echo $_SESSION['kodil']['fullname']; ?>
			</li>
			<li onclick="window.location='../close.php'"><img src="../img/salir.png" width="20px" height="20px" class="icon">Salir</li>
		</section>
		<section class="menu" id="menu" onclick="modal('menu')" style="display: none;">
			<div class="content-menu" onclick="modal('menu')">
				<div class="content-img"><img src="../<?php echo $_SESSION['kodil']['img']; ?>" width='140px' height='140px'></div>
				<div class="content-name"><?php echo $_SESSION['kodil']['fullname'] ?></div>
				<div class="content-email"><?php echo $_SESSION['kodil']['email'] ?></div>
				<br><hr>
				<nav>
					<li onclick="window.location='administra_cuenta.php'">Administrar cuentas</li>
					<li onclick="window.location='administra_tarjeta.php'">Administrar tarjetas</li>
					<li onclick="window.location='register.php'">Registrar cuenta</li>
					<li onclick="window.location='../index.php'">Página de usuario</li>
				</nav>
			</div>
		</section>
	</header>

	<section class="content">
		<div class="option" onclick="window.location='register.php'">
			<div class="content-img"><img src="img/registrar.png" width="100%" height="100%"></div>
			<div class="texto">REGISTRAR CUENTA</div>
		</div>
		<div class="option" onclick="window.location='administra_cuenta.php'">
			<div class="content-img"><img src="img/administra_cuenta.png" width="100%" height="100%"></div>
			<div class="texto">ADMINISTRAR CUENTAS</div>
		</div>
		<div class="option" onclick="window.location='administra_tarjeta.php'">
			<div class="content-img"><img src="img/administra_tarjeta.png" width="100%" height="100%"></div>
			<div class="texto">ADMINISTRAR TARJETAS</div>
		</div>
	</section>
	<?php
}

?>

</body>
</html>