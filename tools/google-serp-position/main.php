<?php
	$application->setPageName("Google SERP Position");
	$application->setProcessor("process.php");

	$application->requestForm(NONE);
	$application->overrideForm("override.php");

	$application->setInputNames('auto');
?>