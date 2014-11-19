<?php
	$application->setPageName("Predict Backlinks");
	$application->setProcessor("process.php");

	$application->requestForm(SINGLE_DOMAIN_NAME);

	$application->setInputNames(array("url"));
	$application->setInputRequired(array("url"));
	$application->setInputValidation(VALID_URL);
	$application->setInputValidates(array("url"));
?>