<?php

	session_start();
	$_SESSION['kodil']=null;
	return header("location:index.php");

?>