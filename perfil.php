<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="author" content="Julio César Villanueva Ontiveros">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>PERFIL</title>
	<link rel="stylesheet" type="text/css" href="css/header.css">
	<link rel="stylesheet" type="text/css" href="css/perfil.css">
	<script type="text/javascript" src="js/app.js"></script>
	<script type="text/javascript" src="js/cuenta.js"></script>
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
		<section class="content-formu">
			<fieldset><legend style="margin-left: 10px;">ACCESO A TÚ CUENTA - PARA CAMBIAR OTROS DATOS CONSULTE UNA SUCURSAL</legend>
				<div class="content-caja">
					<input type="text" placeholder="Usuario" class="texto" value="Usuario" style="border-radius: 5px 0px 0px 5px; margin: 10px 0px 10px 10px" readonly required>
					<input type="text" placeholder="Contraseña" class="caja" value="<?php echo $_SESSION['kodil']['user']; ?>" style="border-radius: 0px 5px 5px 0px; margin: 10px 10px 10px 0px" id="user">
					<input type="submit" placeholder="Contraseña" class="btn" value="Guardar cambios" style="border-radius: 0px 5px 5px 0px; margin: 10px 10px 10px 0px" onclick="update_user()">
				</div>
				<div class="aviso" id="aviso"></div>
				<div class="content-caja">
					<input type="text" placeholder="Usuario" class="texto" value="Contraseña actual" style="border-radius: 5px 0px 0px 5px; margin: 10px 0px 10px 10px" readonly>
					<input type="password" placeholder="Contraseña" class="caja" value="" style="border-radius: 0px 5px 5px 0px; margin: 10px 10px 10px 0px;" id="password">
					<input type="text" placeholder="Usuario" class="texto" value="Cambiar contraseña" style="border-radius: 5px 0px 0px 5px; margin: 10px 0px 10px 10px" readonly required>
					<input type="password" placeholder="Contraseña" class="caja" style="border-radius: 0px 5px 5px 0px; margin: 10px 10px 10px 0px;" id="password-new">
				</div>
				<div class="content-caja">
					<img width="200px" height="200px" src="<?php echo $_SESSION['kodil']['img']; ?>" class="img">
					<input type="file" id="file" width="200px" height="200px" style="position: absolute;">
				</div>
			</fieldset>
		</section>
	</section>
	<?php
}

?>

</body>
</html>