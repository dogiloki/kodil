<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="author" content="Julio César Villanueva Ontiveros">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>ADMINISTRACIÓN DE CUENTA</title>
	<link rel="stylesheet" type="text/css" href="css/header.css">
	<link rel="stylesheet" type="text/css" href="css/administracion_cuenta.css">
	<script type="text/javascript" src="js/cuenta.js"></script>
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

	<section class="content-administrar">
		<table width="100%" border="1">
			<tr>
				<th>Nombre</th>
				<th>Email</th>
				<th>Fecha de nacimiento</th>
				<th>Telefono</th>
				<th>Cuenta</th>
				<th>Saldo</th>
				<th>Registrado</th>
				<th>Opciones</th>
			</tr>
			<?php
			$rs=mysqli_query($conexion,"SELECT * FROM users ORDER BY register DESC");
			while($row=mysqli_fetch_array($rs)){
				echo "<tr id=".$row['id']."><td>".$row['name']." ".$row['surname']."</td>";
				echo "<td>".$row['email']."</td>";
				echo "<td>".$row['birth']."</td>";
				echo "<td>".$row['phone']."</td>";
				echo "<td>".$row['account']."</td>";
				echo "<td>$".number_format($row['saldo'])."</td>";
				echo "<td>".$row['register']."</td>";
				?>
				<td class="opciones" align="center">
					<?php
					if($row['active']=='0'){
						?><img src='img/activar.png' width='20px' height='20px' id='activar<?php echo $row['id']; ?>' title='ACTIVADO' onclick="active('<?php echo $row['id']; ?>')"><?php
					}else{
						?><img src='img/desactivar.png' width='20px' height='20px' id='activar<?php echo $row['id']; ?>' title='ACTIVADO' onclick="active('<?php echo $row['id']; ?>')"><?php
					}
					if($row['admin']=='0'){
						?><img src='img/no_admin.png' width='20px' height='20px' id='admin<?php echo $row['id']; ?>' title='ADMIN' onclick="admin('<?php echo $row['id']; ?>')"><?php
					}else{
						?><img src='img/admin.png' width='20px' height='20px' id='admin<?php echo $row['id']; ?>' title='ADMIN' onclick="admin('<?php echo $row['id']; ?>')"><?php
					}
					?>
					<img src="img/editar.png" width="20px" height="20px" id="editar" title="EDITAR">
					<img src="img/eliminar.png" width="20px" height="20px" id="eliminar" title="ELIMINAR" onclick="eliminar('<?php echo $row['id']; ?>')">
				</td></tr>
				<?php
			}
			?>
		</table>
	</section>
	<?php
}

?>

</body>
</html>