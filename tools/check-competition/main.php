<?php
	$application->setPageName("Check Competition");
	$application->setProcessor("process.php");

	$application->requestForm(TAGS_TEXT);

	$application->setInputNames(array("tags"));
	$application->setInputRequired(array("tags"));
	$application->setInputValidation(VALID_TAGS);
	$application->setInputValidates(array("tags"));
?>