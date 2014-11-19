<?php
	$application->setPageName("Generate Keywords");
	$application->setProcessor("process.php");

	$application->requestForm(SINGLE_TEXTAREA);

	$application->setInputNames(array("list"));
	$application->setInputRequired(array("list"));
	$application->setInputValidates(array("list"));
	$application->setMemory("Description", "Enter a list of key words or phrases to generate more");
?>