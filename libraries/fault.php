<?php
	function doFault() {
		header("Location: index.php?error=1");
		exit;
	}
?>