<?php

	if(isset($_GET['id'])){
		echo "<a href='".$_GET['id']."' download='Kodil-QR-".mt_rand(0,99999)."'><img src='".$_GET['id']."'></a>";
	}

?>