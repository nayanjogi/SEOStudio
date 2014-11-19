<?php
	$application->setPageName("Generate Robots.txt");
	$application->setProcessor("process.php");

	$application->requestForm(NONE);
	$application->overrideForm("override.php");

	$application->setInputNames('auto');
?>
