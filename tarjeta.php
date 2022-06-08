<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="author" content="Julio César Villanueva Ontiveros">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>TARJETA</title>
	<link rel="stylesheet" type="text/css" href="css/header.css">
	<link rel="stylesheet" type="text/css" href="css/tarjeta.css">
	<script type="text/javascript" src="js/app.js"></script>
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
		<div class="option" title="TARJETA DE DÉBITO">
			<div class="texto" onclick="window.location='debito.php'">TARJETA DE DÉBITO</div>
			<div class="content-img">
				<img src="img/debito.png" width="100%" height="100%" onclick="window.location='debito.php'">
				<?php
					$rs=mysqli_query($conexion,"SELECT * FROM debito WHERE account='".$_SESSION['kodil']['account']."'");
					while($row=mysqli_fetch_array($rs)){
						echo "<div class='digitos'>".$row['digitos']."</div>";
						echo "<div class='fecha'>Válida hasta<br>".$row['vencimiento']."</div>";
					}
					if($_SESSION['kodil_debito']['cancelada']!='1'){
						?>
						<input type="button" value="ACTIVAR" style="width: 100%; padding: 10px; cursor: pointer; border: 1px solid #c2c2c2; background: #353535; color: #f5f5f5; font-size: 20px; border-radius: 10px;" onclick="desbloquear('debito')" id="btn_active">
						<?php
					}
				?>
				<progress value="0" max="180" style="width: 100%;" id="progress"></progress><br>
				<div id="text_progress"></div><br>
				<div id="datos_active"></div>
			</div>
		</div>
		<div class="option" title="TARJETA DE CRÉDITO">
			<div class="texto">TARJETA DE CRÉDITO</div>
			<div class="content-img">
				<img src="img/credito.png" width="100%" height="100%">
				<?php
					if($_SESSION['kodil']['tarjeta_credito']=='1'){
						$rs=mysqli_query($conexion,"SELECT * FROM credito WHERE account='".$_SESSION['kodil']['account']."'");
						while($row=mysqli_fetch_array($rs)){
							echo "<div class='digitos'>".$row['digitos']."</div>";
						}
					}else{
						echo "<div class='digitos' style='left: 70px;'>SOLICITAR UNA TARJETA</div>";
					}
				?>
			</div>
		</div>
	</section>

	<!-- Activar tarjeta -->

	<script type="text/javascript">
		var tiempo=60;
		function progreso(){
			$("#progress").val($("#progress").val()-1);
			tiempo--;
			if(tiempo<=9){
				$("#text_progress").html("Tiempo activo: 0"+Math.floor($("#progress").val()/60)+":0"+tiempo);
			}else{
				$("#text_progress").html("Tiempo activo: 0"+Math.floor($("#progress").val()/60)+":"+tiempo);
			}
			if(tiempo==0){
				tiempo=60;
			}
			if($("#progress").val()==0){
				modal("btn_active");
			}else{
				setTimeout('progreso()',1000);
			}
		}

		function desbloquear(tarjeta){
			$.ajax({
				url:"controllers/tarjeta.php?v=debloquear",
				method:"post",
				data:{
					'tarjeta':tarjeta
				},
				success:function(value){
					if(value=='error' || value=='errorerror'){
						alert("ERROR AL ACTIVAR INTENTE MÁS TARDE");
					}else{
						modal("btn_active");
						tiempo=60;
						$("#progress").val(180);
						$("#datos_active").html(value);
						progreso();
					}
				}
			})
			.fail(function(){
				alert("ERROR AL ACTIVAR INTENTE MÁS TARDE");
			})
}
	</script>

	<?php
}

?>

</body>
</html>