<?php
	ob_start();
	
	session_start();
	session_destroy();
	
	header("Location: ../");
	exit("Redirecting to <a href='../'>../</a>.");
?>