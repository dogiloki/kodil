<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="author" content="Julio César Villanueva Ontiveros">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>KODIL</title>
	<link rel="stylesheet" type="text/css" href="css/header.css">
	<link rel="stylesheet" type="text/css" href="css/register.css">
	<script type="text/javascript" src="js/register.js"></script>
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

	<section class="content-register">
		<!--<div class="title">REGISTRARSE</div>-->
		<div class="register">
			<div style="display: inline-block; width: 100%; margin-left: 10px;">
				<div class="content-caja">
					<div class="texto">Nombre(s)</div><input type="text" id="name" placeholder="Nombre" class="caja" required>
				</div>
				<div class="content-caja">
					<div class="texto">Apellidos(s)</div><input type="text" id="surname" placeholder="Apellido" class="caja" required>
				</div>
				<div class="content-caja">
					<div class="texto">Correo electrónico</div><input type="email" id="email" placeholder="Correo Electrónico" class="caja" required>
				</div>
				<div class="content-caja">
					<div class="texto">Fecha de nacimiento</div><input type="date" id="birth" class="caja" title="Fecha de nacimiento" alt="Fecha de nacimiento" required>
				</div>
				<div class="content-caja">
					<div class="texto">Número de telefono</div><input type="text" id="phone" placeholder="Número de telefono" class="caja" required>
				</div>
				<div class="content-caja">
					<div class="texto"><input type="checkbox" id="admin">Administrador</div>
				</div>
				<div class="content-caja">
					<div class="texto">Número de cuenta</div>
					<select id="debito" class="caja" required>
						<?php
						$rs=mysqli_query($conexion,"SELECT * FROM digitos WHERE id_user=''");
						while($row=mysqli_fetch_array($rs)){
							echo "<option value='".$row['digito']."'>".$row['digito'];
						}
						?>
					</select>
				</div>
			</div>
			<div style="display: inline-block; width: 100%;">
				<div class="content-caja">
					<div class="texto">Sexo</div>
					<select id="sexo" class="caja">
						<option value="Hombre">Hombre
						<option value="Mujer">Mujer
					</select>
				</div>
				<div class="content-caja">
					<div class="texto">Usuario</div><input type="text" id="user" placeholder="Usuario" class="caja" required>
				</div>
				<div class="content-caja">
					<div class="texto">Contraseña</div><input type="password" id="password" placeholder="Contraseña" class="caja" required>
				</div>
				<div class="content-caja">
					<div class="texto">Repite contraseña</div><input type="password" id="password_veri" placeholder="Repetir contraseña" class="caja" required>
				</div>
				<div class="content-caja">
					<div class="texto">Tipo de cuenta</div>
					<select id="tipo_account" class="caja">
						<option value="corriente">Cuenta corriente
						<option value="ahorro">Cuenta remunerada o de ahorro
						<option value="nomina">Cuenta de nómina
						<option value="valores">Cuenta de valores
						<option value="empresas">Cuenta para empresas y negocios
					</select>
				</div>
				<div class="content-caja">
					<div class='aviso' id='aviso'></div>
					<input type="submit" value="LISTO!!!" onclick="register()" class="btn">
				</div>
			</div>
	</section>
	<?php
}

?>

</body>
</html>