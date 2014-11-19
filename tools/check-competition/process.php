<?php
	$tags = $application->getInput("tags");

	$googleResult = file_get_html("http://www.google.com/search?q=" . urlencode($_POST['tags']));
	$amountResults = $googleResult->find("div[id=resultStats]", 0);
	
	$amountResults = str_replace("About ", "", $amountResults->innertext);
	$amountResults = str_replace(" results ", "", $amountResults);
	$amountResults = str_replace(",", "", $amountResults);
	$amountResults = preg_replace('/[^0-9]/', '', $amountResults);
	$amountResults = intval($amountResults);

	$wentOver = false;
	$competitionPercentile = round(($amountResults / 15000000) * 100);
	
	if($competitionPercentile > 100) { $competitionPercentile = 100; $wentOver = true; }
?>
		<div class="pageHeader">
			<div class="container">
				<h2><?=$lang->translate($title, "tool-name");?></h2>
			</div>
		</div>

		<div class="container">
			<div class="row">
				<div class="col-lg-12 col-md-12 col-sm-12">
					<div class="resultsBox1">
						<div class="progress progress-striped active" style="margin-bottom: 0; border-radius: 0px; -webkit-border-radius: 0px; -moz-border-radius: 0px;">
							<div class="progress-bar"  role="progressbar" aria-valuenow="<?=$competitionPercentile;?>" aria-valuemin="0" aria-valuemax="100" style="width: <?=$competitionPercentile;?>%">
								<span class="sr-only"><?=$competitionPercentile;?>%</span>
							</div>
						</div>
					</div>
				</div>
			</div>

			<h4><?=$lang->translate("Results", "header");?></h4>

			<p>
				<b><?=ucfirst($tags);?>:</b> &nbsp;<?=str_replace("%n", number_format($amountResults), $lang->translate("We found approximately %n websites that are competing for your tags.", "variable"));?>
			</p>

			<div style="height: 30px;"></div>
			<div style="height: 30px;" class="visible-lg visible-md"></div>

			<a href="<?=$path;?>./tools.php" class="btn btn-lg btn-success"><?=$lang->translate("Browse More Tools", "button");?></a>
			&nbsp;
			<a href="" class="btn btn-lg btn-danger"><?=$lang->translate("Check Different Tags", "button");?></a>
		</div>