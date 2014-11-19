<?php
	$data = array();
	$posts = array($application->getInput("url1"), $application->getInput("url2"));
	if($application->getInput("url3") != "") {
		$posts[] = $application->getInput("url3");
	}

	foreach($posts as $url) {
		$domain = str_replace(array("http://","https://"), "", $url);

		$wwwLink = parse_url("http://".$domain);
		$wwwLink = str_replace("www.", "", $wwwLink['host']);
		
		$googleResult1 = file_get_html("https://www.google.com/search?q=" . urlencode('"'.$wwwLink.'"'));
		$googleResult2 = file_get_html("https://www.google.com/search?q=" . urlencode('"www.'.$wwwLink.'"'));
		
		$googleResults1 = $googleResult1->find('div[id=resultStats]', 0)->innertext;
		$googleResults1 = str_replace(array("About ", " results", ","), "", $googleResults1);
		$googleResults1 = preg_replace('/[^0-9]/', '', $googleResults1);
		
		$googleResults2 = $googleResult2->find('div[id=resultStats]', 0)->innertext;
		$googleResults2 = str_replace(array("About ", " results", ","), "", $googleResults2);
		$googleResults2 = preg_replace('/[^0-9]/', '', $googleResults2);
		
		$backlinks = $googleResults1 + $googleResults2;
        
        $googleResult1 = file_get_html("https://www.google.com/search?q=" . urlencode('site:'.$wwwLink.''));

        $googleResults12 = $googleResult1->find('div[id=resultStats]', 0)->innertext;
        $googleResults12 = str_replace(array("About ", " results", ","), "", $googleResults1);
        $googleResults12 = preg_replace('/[^0-9]/', '', $googleResults1);

        $backlinks = $backlinks - intval($googleResults12);

		$data[] = array(
			'domain' => $domain,
			'wwwlink' => $wwwLink,
			'backlinks' => $backlinks
		);
	}
?>
		<div class="pageHeader">
			<div class="container">
				<h2><?=$lang->translate($title, "tool-name");?></h2>
			</div>
		</div>

		<div class="container">
			<div class="row">
				<div class="col-md-4 col-sm-4 col-lg-4">
					<div class="resultsBox1">
						<span class="graduatedText">
							<?=$data[0]['wwwlink'];?>
						</span>

						<div class="bigNumber">
							<?=number_format($data[0]['backlinks']);?>
						</div>

						<span class="graduatedText">
							<?=$lang->translate("backlinks");?>
						</span>
					</div>
				</div>
				<div class="col-md-4 col-sm-4 col-lg-4">
					<div class="resultsBox1">
						<span class="graduatedText">
							<?=$data[1]['wwwlink'];?>
						</span>

						<div class="bigNumber">
							<?=number_format($data[1]['backlinks']);?>
						</div>

						<span class="graduatedText">
							<?=$lang->translate("backlinks");?>
						</span>
					</div>
				</div>
				<?php if(count($data) > 2) { ?>
				<div class="col-md-4 col-sm-4 col-lg-4">
					<div class="resultsBox1">
						<span class="graduatedText">
							<?=$data[2]['wwwlink'];?>
						</span>

						<div class="bigNumber">
							<?=number_format($data[2]['backlinks']);?>
						</div>

						<span class="graduatedText">
							<?=$lang->translate("backlinks");?>
						</span>
					</div>
				</div>
				<?php } ?>
			</div>

			<a href="<?=$path;?>./tools.php" class="btn btn-lg btn-success"><?=$lang->translate("Browse More Tools", "button");?></a>
			&nbsp;
			<a href="" class="btn btn-lg btn-danger"><?=$lang->translate("Check Another Website", "button");?></a>
		</div>