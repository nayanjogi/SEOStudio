<?php
	$url = $application->getInput("url");

	$domain = parse_url($url);

    if(!isset($domain['host'])) {
        header("Location: ?page=blog-backlinks-lookup");
        exit;
    }
	$domain = $domain['host'];

	$wwwLink = parse_url("http://".$domain);
	$wwwLink = str_replace("www.", "", $wwwLink['host']);
	
	$googleResult1 = file_get_html("https://www.google.com/search?q=" . urlencode('"'.$wwwLink.'"') . "+" . urlencode('"wordpress"'));
	$googleResult2 = file_get_html("https://www.google.com/search?q=" . urlencode('"www.'.$wwwLink.'"') . "+" . urlencode('"wordpress"'));
	
	$googleResults1 = $googleResult1->find('div[id=resultStats]', 0)->innertext;
	$googleResults1 = str_replace(array("About ", " results", ","), "", $googleResults1);
	$googleResults1 = preg_replace('/[^0-9]/', '', $googleResults1);
	
	$googleResults2 = $googleResult2->find('div[id=resultStats]', 0)->innertext;
	$googleResults2 = str_replace(array("About ", " results", ","), "", $googleResults2);
	$googleResults2 = preg_replace('/[^0-9]/', '', $googleResults2);
	
	$backlinks = number_format($googleResults1 + $googleResults2);
	
	$string = $lang->translate("The domain %1 has %2 blog backlinks.", "variable");
	$string = str_replace("%1", "<strong>$domain</strong>", $string);
	$string = str_replace("%2", "<strong>$backlinks</strong>", $string);
?>
		<div class="pageHeader">
			<div class="container">
				<h2><?=$lang->translate($title, "tool-name");?></h2>
			</div>
		</div>

		<div class="container">
			<?php
				echo $string;
			?>

			<hr />

			<a href="<?=$path;?>./tools.php" class="btn btn-lg btn-success"><?=$lang->translate("Browse More Tools", "button");?></a>
			&nbsp;
			<a href="" class="btn btn-lg btn-danger"><?=$lang->translate("Check Another Website", "button");?></a>
		</div>