<?php

	include("../conexion.php");
	if($_GET['tabla']=='user'){
		$rs_user=mysqli_query($conexion,"SELECT * FROM users WHERE id='".$_POST['id']."'");
		while($row_user=mysqli_fetch_array($rs_user)){
			if($_GET['v']=='fullname'){
				echo "<span style='white-space: nowrap;'>".$row_user['name']." ".$row_user['surname']."</span>";
				return 0;
			}
			if($_GET['v']=='img'){
				echo $row_user['img'];
				return 0;
			}
			if($_GET['v']=='saldo'){
				echo $row_user['saldo'];
				return 0;
			}
			if($_GET['v']=='credito_digitos'){
				if($row_user['tarjeta_credito']=='1'){
					$rs_credito=mysqli_query($conexion,"SELECT * FROM credito WHERE account='".$row_user['account']."'");
					while($row_credito=mysqli_fetch_array($rs_credito)){
						echo $row_credito['digitos'];
						return 0;
					}
				}else{
					echo "SOLICITAR UNA TARJETA";
					return 0;
				}
			}
		}
	}
	if($_GET['tabla']=='debito'){
		$rs_debito=mysqli_query($conexion,"SELECT * FROM debito WHERE account='".$_POST['account']."'");
		while($row_debito=mysqli_fetch_array($rs_debito)){
			if($_GET['v']=='debito_digitos'){
				echo $row_debito['digitos'];
				return 0;	
			}
		}
	}

?>