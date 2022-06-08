<?php

	include("../../conexion.php");

	if(isset($_POST)){
		$rs_user=mysqli_query($conexion,"SELECT * FROM users WHERE id='".$_POST['id']."'");
		while($row_user=mysqli_fetch_array($rs_user)){
			if($_GET['v']=='active'){
				if($row_user['active']=='0'){
					mysqli_query($conexion,"UPDATE users SET active='1' WHERE id='".$_POST['id']."'");
					echo "img/desactivar.png";
					return 0;
				}else{
					mysqli_query($conexion,"UPDATE users SET active='0' WHERE id='".$_POST['id']."'");
					echo "img/activar.png";
					return 0;
				}
			}
			if($_GET['v']=='admin'){
				if($row_user['admin']=='0'){
					mysqli_query($conexion,"UPDATE users SET admin='1' WHERE id='".$_POST['id']."'");
					echo "img/admin.png";
					return 0;
				}else{
					mysqli_query($conexion,"UPDATE users SET admin='0' WHERE id='".$_POST['id']."'");
					echo "img/no_admin.png";
					return 0;
				}
			}
			if($_GET['v']=='delete'){
				mysqli_query($conexion,"CALL eliminar('".$_POST['id']."','".$row_user['account']."')") or die ("error_eliminar");
				echo "eliminado";
				return 0;
			}
		}
	}

?>