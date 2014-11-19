<?php

	$code = '';

		$engines = array(
			'Googlebot',
			'Googlebot-Image',
			'Googlebot-Mobile',
			'MSNBot',
			'PSBot',
			'Slurp',
			'Yahoo-MMCrawler',
			'yahoo-blogs/v3_9',
			'teoma',
			'Scrubby',
			'ia_archiver'
		);
		
		foreach($engines as $i) {
			$code .= 'User-agent: ' . $i . PHP_EOL;
			
			$code .= 'Disallow: ';
			if(!isset($_POST[$i])) $code .= "/";
			$code .= PHP_EOL . PHP_EOL;
		}
	
?>
	<div class="pageHeader">
		<div class="container">
			<h2><?=$lang->translate($title, "tool-name");?></h2>
		</div>
	</div>

	<div class="container">
		
		<div class="well">
			<pre><?=$code;?></pre>
		</div>

		<hr />

		<a href="<?=$path;?>./tools.php" class="btn btn-lg btn-success"><?=$lang->translate("Browse More Tools", "button");?></a>
		
	</div>