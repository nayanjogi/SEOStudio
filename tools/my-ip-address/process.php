<?php
	$ip = $_SERVER['REMOTE_ADDR'];

	$string = $lang->translate("Your public IP address is %1.", "variable");
	$string = str_replace("%1", "<strong>".$ip."</strong>", $string);
?>
		<div class="pageHeader">
			<div class="container">
				<h2><?=$lang->translate($title, "tool-name");?></h2>
			</div>
		</div>

		<div class="container">
			
			<?=$string;?>

			<hr/>

			<a href="<?=$path;?>./tools.php" class="btn btn-lg btn-success"><?=$lang->translate("Browse More Tools", "button");?></a>
		</div>