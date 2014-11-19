<?php
	$application->setPageName("Obfuscate Email");
	$application->setProcessor("process.php");

	$application->requestForm(EMAIL);

	$application->setInputNames(array("email"));
	$application->setInputRequired(array("email"));
?>