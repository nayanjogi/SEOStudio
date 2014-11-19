<?php
	$url = $application->getInput("url");

	$domain = str_replace(array("http://","https://"), "", $url);

	$wwwLink = parse_url("http://".$domain);
	$wwwLink = str_replace("www.", "", $wwwLink['host']);
	
	require_once("libraries/pagerank.php");

	$pr = new GooglePageRankChecker();
	$pageRank = $pr->getRank($wwwLink);

	if(!$pageRank) $pageRank = 0;
	if($pageRank > 9) $pageRank = 9;
	
?>
		<div class="pageHeader">
			<div class="container">
				<h2><?=$lang->translate($title, "tool-name");?></h2>
			</div>
		</div>

		<div class="container">
			<div class="resultsBox1">
				<span class="graduatedText">
					<?=$wwwLink;?>
				</span>
				<div class="bigNumber">
					<?=number_format($pageRank);?>
				</div>
				<span class="graduatedText">
					<?=$lang->translate("Google PageRank", "unit");?>
				</span>
			</div>

			<a href="<?=$path;?>./tools.php" class="btn btn-lg btn-success"><?=$lang->translate("Browse More Tools", "button");?></a>
			&nbsp;
			<a href="" class="btn btn-lg btn-danger"><?=$lang->translate("Check Another Website", "button");?></a>
		</div>