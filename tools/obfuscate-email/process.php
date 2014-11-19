<?php
	$email = $application->getInput('email');
	
	$link = $email;
	$obfuscatedLink = "";
	$obfuscatedLink2 = "";
	for ($i=0; $i<strlen($link); $i++){
		$obfuscatedLink .= "&amp;#" . ord($link[$i]) . ";";
		$obfuscatedLink2 .= "&#" . ord($link[$i]) . ";";
	}
?>

	<div class="pageHeader">
		<div class="container">
			<h2><?=$lang->translate($title, "tool-name");?></h2>
		</div>
	</div>

	<div class="container">
		<div class="row">
			<div class="col-md-6">
				<?=$lang->translate("Plain obfuscated email:", "header");?><br /><br />
				<pre><?=$obfuscatedLink;?></pre>
				<hr />

				<strong><?=$lang->translate("Preview:", "label");?></strong> <?=$obfuscatedLink2;?>
			</div>
			<div class="col-md-6">
				<?=$lang->translate("Obfuscated email in HTML link:", "header");?><br /><br />
				<pre>&lt;a href="mailto:<?=$obfuscatedLink2;?>"&gt;<?=$obfuscatedLink2;?>&lt;/a&gt;</pre>
				<hr />

				<strong><?=$lang->translate("Preview:", "label");?></strong> <a href="mailto:<?=$obfuscatedLink2;?>"><?=$obfuscatedLink2;?></a>
			</div>
		</div>

		<hr />

		<a href="<?=$path;?>./tools.php" class="btn btn-lg btn-success"><?=$lang->translate("Browse More Tools", "button");?></a>
		&nbsp;
		<a href="" class="btn btn-lg btn-danger"><?=$lang->translate("Obfuscate Another Email", "button");?></a>
	</div>