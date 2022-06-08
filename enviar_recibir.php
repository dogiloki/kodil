<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="author" content="Julio César Villanueva Ontiveros">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>ENVIAR REBICIR</title>
	<link rel="stylesheet" type="text/css" href="css/header.css">
	<link rel="stylesheet" type="text/css" href="css/enviar_recibir.css">
	<script type="text/javascript" src="js/app.js"></script>
	<script type="text/javascript" src="js/enviar_recibir.js"></script>
</head>
<body>

<?php

include("conexion.php");
session_start();
if(!isset($_SESSION['kodil']['id'])){
	?>
	<header>
		<img src="img/logo.png" width="180px" height="40px" class="logo" onclick="window.location='index.php'">
		<section class="header">
			<li onclick="window.location='register.php'"><img src="img/registrarse.png" width="20px" height="20px" class="icon">Registrarse</li>
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
		<div class="option">
			<nav class="menu">
				<div class="texto" id="enviar" onclick="cambio('enviar')">ENVÍAR DINERO</div>
				<div class="texto" id="recibir" onclick="cambio('recibir')">RECIBIR DINERO</div>
			</nav>

			<div class="content-datos" id='content-enviar'>
				<div class="content-formu">
					<div class="title-formu">ENVÍAR DINERO</div><hr style="color: #c2c2c2"><br>
					<div class="content-caja">
						<div class="texto-formu">Número de cuenta (obligatorio en envío directo)</div>
						<input type="text" placeholder="Número de cuenta con separado" id="cuenta-enviar" class="caja-formu" autocomplete="off">
					</div>
					<div class="content-caja">
						<div class="texto-formu">Cantidad de dinero</div>
						<input type="number" placeholder="Cantidad de dinero" id="cantidad-enviar" class="caja-formu" autocomplete="off">
					</div>
					<div class="content-caja">
						<div class="texto-formu">Tú contraseña</div>
						<input type="password" placeholder="Tú contraseña" id="password-enviar" class="caja-formu" autocomplete="off">
					</div>
					<div class="aviso" id="aviso"></div>
					<div class="qr" id="qr" title="Descargar"></div>
					<div class="content-btn">
						<input type="button" value="Envío por codigo QR" class="btn-formu" onclick="enviar('si')" id='btn-enviar-qr' title="Se cobrá manualmente">
						<input type="button" value="Envío directo" class="btn-formu" onclick="enviar('no')" id='btn-enviar' title="Se transfiere al instante">
					</div>
					<div class="content-btn">
						<div style="color: green;">AL generar QR el dinero será retirado de tú cuenta cuando sea recibido, y aparecera en tus movimientos</div>
					</div>
				</div>
			</div>
			<div class="content-datos" id='content-recibir' style="display: none;">
				<div class="content-formu">
					<div class="title-formu">RECIBIR DINERO</div><hr style="color: #c2c2c2"><br>
					<div class="content-caja">
						<div class="texto-formu">Eliger código QR</div>
						<input type="file" id="file-recibir" class="caja-formu">
					</div>
					<div class="content-caja">
						<div class="texto-formu">Tú contraseña</div>
						<input type="password" placeholder="Tú contraseña" id="password-recibir" class="caja-formu" autocomplete="off">
					</div>
					<div class="aviso" id="aviso-qr"></div>	
					<div class="info" id="info" title="Información"></div>
					<div class="content-btn">
						<input type="button" value="Cobrár dinero" class="btn-formu" onclick="recibir()" id='btn-recibir' title="Cobrar dinero">
					</div>
					<div class="content-btn">
						<div style="color: green;">AL generar QR el dinero será retirado de tú cuenta cuando sea recibido, y aparecera en tus movimientos</div>
					</div>
				</div>
			</div>
		</div>
	</section>
	<?php
}
?>

</body>
</html>