<?php
	$url = $application->getInput("url");

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
	
	$totalBacklinks = $googleResults1 + $googleResults2;

	// find domain age //

	date_default_timezone_set("America/Phoenix");

	$created_timestamp = 0;

	$whoisResults = get_html_data("http://api.webfector.com/public/whois?domain={$wwwLink}");
	$whois = json_decode($whoisResults, true);

	$created_timestamp = strtotime(str_replace("T", " ", $whois['WhoisRecord']['createdDate']));
	$registeredYear = date("Y", $created_timestamp);

	$maxYears = date("Y") - 1984;
	$numberYears = date("Y") - $registeredYear;
	if($numberYears == 0) $numberYears = 1;

	$backlinksPerYear = ($totalBacklinks / $numberYears);
	$backlinksNextYear = $totalBacklinks + $backlinksPerYear + ($backlinksPerYear * (10 / ($maxYears - $numberYears)));
	$backlinksNextNextYear = $totalBacklinks + $backlinksPerYear + $backlinksPerYear + ($backlinksPerYear * (30 / ($maxYears - $numberYears)));
?>
		<div class="pageHeader">
			<div class="container">
				<h2><?=$lang->translate($title, "tool-name");?></h2>
			</div>
		</div>

		<div class="container">
			<div class="row">
				<div class="col-lg-4 col-md-4 col-sm-4">
					<div class="resultsBox1">
						<span class="graduatedText">
							<?=$wwwLink;?>
						</span>

						<div class="bigNumber">
							<?=number_format($backlinksPerYear);?>
						</div>

						<span class="graduatedText">
							<?=$lang->translate("backlinks per year", "unit");?>
						</span>
					</div>
				</div>
				<div class="col-lg-4 col-md-4 col-sm-4">
					<div class="resultsBox1">
						<span class="graduatedText">
							<?=$wwwLink;?>
						</span>

						<div class="bigNumber">
							<?=number_format($backlinksNextYear);?>
						</div>

						<span class="graduatedText">
							<?=$lang->translate("backlinks in 1 year", "unit");?>
						</span>
					</div>
				</div>
				<div class="col-lg-4 col-md-4 col-sm-4">
					<div class="resultsBox1">
						<span class="graduatedText">
							<?=$wwwLink;?>
						</span>

						<div class="bigNumber">
							<?=number_format($backlinksNextNextYear);?>
						</div>

						<span class="graduatedText">
							<?=$lang->translate("backlinks in 2 years", "unit");?>
						</span>
					</div>
				</div>
			</div>

			<a href="<?=$path;?>./tools.php" class="btn btn-lg btn-success"><?=$lang->translate("Browse More Tools", "button");?></a>
			&nbsp;
			<a href="" class="btn btn-lg btn-danger"><?=$lang->translate("Check Another Website", "button");?></a>
		</div>