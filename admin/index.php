<?php
	require "i.header.php";

	$page = "home";
	if(isset($_GET['page'])) $page = $_GET['page'];

	if(!file_exists("docs/{$page}.php")) {
		$page = "not-found";
	}

	require "docs/{$page}.php";

	require "i.footer.php";
?>
