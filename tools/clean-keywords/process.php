<?php
	$list = $application->getInput("list");

	// First, let's format the list into a code-readable solution.
	
	$list = str_replace("\r\n", "\n", $list);
	$list = str_replace(",", "\n", $list);
	
	// Alright. Now we'll turn the list into an array, and format the words as we do it.
	
	$orig_arr = explode("\n", $list);
	$new_arr = array();
	
	foreach($orig_arr as $key) {
		$str = strtolower(trim($key));
		$str = ucwords($str);

		if(!in_array($str, $new_arr)) {
			$new_arr[] = $str;
		}
	}
	
	// Yes! We're done, now we need to just make the array into a string. 
	
	$result1 = implode(', ', $new_arr); // All in one line
	$result2 = implode('<br />', $new_arr); // All on separate lines
?>
		<div class="pageHeader">
			<div class="container">
				<h2><?=$lang->translate($title, "tool-name");?></h2>
			</div>
		</div>

		<div class="container">
			<div class="row">
				<div class="col-lg-6 col-md-6 col-sm-6">
					<div class="well well-lg">
						<div style="overflow-y: auto; max-height: 400px; padding-right: 10px;">
							<?=$result1;?>
						</div>
					</div>
				</div>
				<div class="col-lg-6 col-md-6 col-sm-6">
					<div class="well well-lg">
						<div style="overflow-y: auto; max-height: 400px; padding-right: 10px;">
							<?=$result2;?>
						</div>
					</div>
				</div>
			</div>

			<a href="<?=$path;?>./tools.php" class="btn btn-lg btn-success"><?=$lang->translate("Browse More Tools", "button");?></a>
			&nbsp;
			<a href="" class="btn btn-lg btn-danger"><?=$lang->translate("Check Another Website", "button");?></a>
		</div>