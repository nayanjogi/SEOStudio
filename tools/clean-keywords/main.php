<?php
	$application->setPageName("Clean Keywords");
	$application->setProcessor("process.php");

	$application->requestForm(SINGLE_TEXTAREA);

	$application->setInputNames(array("list"));
	$application->setInputRequired(array("list"));
	$application->setInputValidates(array("list"));
	$application->setMemory("Description", "Enter a list of keywords that you want to clean");
?>