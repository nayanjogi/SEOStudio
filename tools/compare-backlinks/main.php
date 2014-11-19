<?php
	$application->setPageName("Compare Backlinks");
	$application->setProcessor("process.php");

	$application->requestForm(THREE_DOMAIN_NAMES);

	$application->setInputNames(array("url1", "url2", "url3"));
	$application->setInputRequired(array("url1", "url2"));
	$application->setInputValidation(VALID_URL);
	$application->setInputValidates(array("url1", "url2", "url3"));
?>