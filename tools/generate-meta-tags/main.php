<?php
	$application->setPageName("Generate Meta Tags");
	$application->setProcessor("process.php");

	$application->requestForm(NONE);
	$application->overrideForm("override.php");

	$application->setInputNames(array(
		"description",
		"keywords",
		"robots_index",
		"robots_follow",
		"content_type",
		"language"
	));
?>
