<?php

	include("../conexion.php");
	$debito="";
	$contador=0;
	for($a=1; $a<=16; $a++){
		$contador++;
		$debito.=mt_rand(0,9);
		if($contador==4){
			$contador=0;
			$debito.=" ";
		}
	}
	$debito=substr($debito,0,strlen($debito)-1);
	mysqli_query($conexion,"INSERT INTO digitos VALUES ('','".$debito."')") or die ("ERROR");

?>