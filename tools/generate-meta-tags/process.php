<?php

	$description = $application->getInput("description");
	$keywords = $application->getInput("keywords");
	$robots_index = $application->getInput("robots_index");
	$robots_follow = $application->getInput("robots_follow");
	$content_type = $application->getInput("content_type");
	$language = $application->getInput("language");
	
	$description = str_replace("\r\n", "\n", $description);
	$description = str_replace("\n", "", $description);
	$description = str_replace('"', "", $description);
	
	$keywords = str_replace("\r\n", "\n", $keywords);
	$keywords = str_replace("\n", "", $keywords);
	$keywords = str_replace('"', "", $keywords);
	
	$code = '';

	// Let's compile it all.
	
	$code .= '<meta name="description" content="' . $description . '">' . PHP_EOL;
	$code .= '<meta name="keywords" content="' . $keywords . '">' . PHP_EOL;
	$code .= '<meta name="robots" content="' . $robots_index . ', ' . $robots_follow . '">' . PHP_EOL;
	$code .= '<meta name="language" content="' . $language . '">' . PHP_EOL;
	$code .= '<meta http-equiv="Content-Type" content="' . $content_type . '">' . PHP_EOL;
	
	$code = str_replace("<", "&lt;", $code);
	$code = str_replace(">", "&gt;", $code);
	
	$done = true;
?>
	<div class="pageHeader">
		<div class="container">
			<h2><?=$lang->translate($title, "tool-name");?></h2>
		</div>
	</div>

	<div class="container">
		<pre><?php
				echo $code;
			?></pre>

			<hr />

			<a href="<?=$path;?>./tools.php" class="btn btn-lg btn-success"><?=$lang->translate("Browse More Tools", "button");?></a>
		</div>
	</div>