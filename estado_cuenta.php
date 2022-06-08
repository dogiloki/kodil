<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="author" content="Julio César Villanueva Ontiveros">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>ESTADO DE CUENTA</title>
	<link rel="stylesheet" type="text/css" href="css/header.css">
	<link rel="stylesheet" type="text/css" href="css/estado_cuenta.css">
	<script type="text/javascript" src="js/app.js"></script>
	<script type="text/javascript" src="js/tarjeta.js"></script>
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
				<div class="texto" id="info" onclick="cambio('info')">INFORMACIÓN</div>
				<div class="texto" id="movi" onclick="cambio('movi')">MOVIMIENTOS</div>
			</nav>
			<section class="content-datos" id='content-info' style="display: none;">
				<div class="actualizar">ESTADO DE CUENTA</div>
				<div class="content-info-info">
					<strong class="tilte-info">Tú saldo</strong>
					<div class="info">$<?php echo number_format($_SESSION['kodil']['saldo']); ?></div>
				</div>
				<div class="content-info-info">
					<strong class="tilte-info">Número de cuenta</strong>
					<div class="info"><?php echo $_SESSION['kodil']['account']; ?></div>
				</div>
			</section>
			<section class="content-datos" id='content-movi' style="display: none;">
				<div class="actualizar">ESTADO DE CUENTA</div>
					<?php
						$rs_movi=mysqli_query($conexion,"SELECT * FROM movimientos WHERE realizado='cuenta' AND (desde='".$_SESSION['kodil']['account']."' OR para='".$_SESSION['kodil']['account']."') ORDER BY date DESC");
						while($row_movi=mysqli_fetch_array($rs_movi)){
							?>
							<div class='content-info-movi' style="cursor: pointer;" onclick="modal('<?php echo $row_movi['id']; ?>')">
								<div class='movi' title="Más información"><?php echo utf8_encode($row_movi['tipo_operacion'])." - ".$row_movi['date'] ?></div>
								<section id='<?php echo $row_movi['id']; ?>' style='display: none;' class='mover-movi'>
									<div class="info_movi">
										<div class="text-movi">Desde: <?php echo utf8_encode($row_movi['desde']); ?></div>
										<div class="text-movi">Para: <?php echo utf8_encode($row_movi['para']); ?></div>
										<?php
											if($row_movi['desde']==$_SESSION['kodil']['account']){
												echo "<div class='text-movi' style='color: red;'>Cantidad: -".utf8_encode($row_movi['cantidad'])."</div>";
											}else{
												echo "<div class='text-movi' style='color: green;'>Cantidad: +".utf8_encode($row_movi['cantidad'])."</div>";
											}
										?>
										<div class="text-movi">Fecha: <?php echo utf8_encode($row_movi['date']); ?></div>
									</div>
								</section>
							</div>
							<?php
						}
					?>
			</section>
		</div>
	</section>
	<?php
}

?>

</body>
</html>