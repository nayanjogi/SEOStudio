<?php
	$data = array();
	$posts = array($application->getInput("url1"), $application->getInput("url2"));
	if($application->getInput("url3") != "") {
		$posts[] = $application->getInput("url3");
	}

	require_once("libraries/pagerank.php");

	foreach($posts as $url) {
		$domain = str_replace(array("http://","https://"), "", $url);

		$wwwLink = parse_url("http://".$domain);
		$wwwLink = str_replace("www.", "", $wwwLink['host']);

		$pr = new GooglePageRankChecker();
		$pageRank = $pr->getRank($wwwLink);

		if(!$pageRank) $pageRank = 0;
		if($pageRank > 9) $pageRank = 9;

		$data[] = array(
			'domain' => $domain,
			'wwwlink' => $wwwLink,
			'pagerank' => $pageRank
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
							<?=number_format($data[0]['pagerank']);?>
						</div>

						<span class="graduatedText">
							<?=$lang->translate("Google PageRank", "unit");?>
						</span>
					</div>
				</div>
				<div class="col-md-4 col-sm-4 col-lg-4">
					<div class="resultsBox1">
						<span class="graduatedText">
							<?=$data[1]['wwwlink'];?>
						</span>

						<div class="bigNumber">
							<?=number_format($data[1]['pagerank']);?>
						</div>

						<span class="graduatedText">
							<?=$lang->translate("Google PageRank", "unit");?>
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
							<?=number_format($data[2]['pagerank']);?>
						</div>

						<span class="graduatedText">
							<?=$lang->translate("Google PageRank", "unit");?>
						</span>
					</div>
				</div>
				<?php } ?>
			</div>

			<a href="<?=$path;?>./tools.php" class="btn btn-lg btn-success"><?=$lang->translate("Browse More Tools", "button");?></a>
			&nbsp;
			<a href="" class="btn btn-lg btn-danger"><?=$lang->translate("Check Another Website", "button");?></a>
		</div>