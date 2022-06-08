<?php

	$conexion=mysqli_connect("localhost","root","") or die ("ERROR EN SERVIDOR");
	mysqli_select_db($conexion,"kodil") or die ("ERROR EN BASE DE DATOS");

?>