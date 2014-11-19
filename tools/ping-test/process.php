<?php
	$url = $application->getInput("url");

	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.13) Gecko/20080311 Firefox/2.0.0.13');
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 15);
	$data = curl_exec_follow($ch);

	$args = array(
		round(curl_getinfo($ch, CURLINFO_CONNECT_TIME), 5),
		round(curl_getinfo($ch, CURLINFO_NAMELOOKUP_TIME), 5)
	);

	curl_close($ch);
	
?>
		<div class="pageHeader">
			<div class="container">
				<h2><?=$lang->translate($title, "tool-name");?></h2>
			</div>
		</div>

		<div class="container">
			<?php
				$str1 = $lang->translate("It took %1 seconds to connect to %2.", "variable");
				$str2 = $lang->translate("It took %1 seconds to lookup the DNS of %2.", "variable");

				$str1 = str_replace("%1", $args[0], $str1);
				$str1 = str_replace("%2", $url, $str1);

				$str2 = str_replace("%1", $args[1], $str2);
				$str2 = str_replace("%2", $url, $str2);

				echo $str1 . "<br/><br/>" . $str2;
			?>

			<hr />

			<a href="<?=$path;?>./tools.php" class="btn btn-lg btn-success"><?=$lang->translate("Browse More Tools", "button");?></a>
			&nbsp;
			<a href="" class="btn btn-lg btn-danger"><?=$lang->translate("Check Another Website", "button");?></a>
		</div>