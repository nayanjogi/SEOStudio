<?php
	$list = $application->getInput("list");

	$results = "";
	
	function GetHTML($url) {
		$ch = curl_init();
		$timeout = 5;
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
		$data = curl_exec($ch);
		curl_close($ch);
		return $data;
	}
	
	function getKeywordSuggestionsFromGoogle($keyword) {
		$keywords = array();
		$data = GetHTML('http://suggestqueries.google.com/complete/search?output=firefox&client=firefox&hl=en-US&q='.urlencode($keyword));
		if (($data = json_decode($data, true)) !== null) {
			$keywords = $data[1];
		}

		return $keywords;
	}
	
	$list = str_replace("\r\n", "\n", $list);
	$list = str_replace(",", "\n", $list);
	$list = strtolower($list);
	
	$listarr = explode("\n", $list);
	$wordarr = array();
	
	foreach($listarr as $i) {
		$wordarr[] = trim($i);
	}
	
	$results = array();
	
	foreach($wordarr as $w) {
		$words = getKeywordSuggestionsFromGoogle($w);
		
		foreach($words as $word) {
			$results[] = ucwords($word);
		}
	}

	$words = $results;
?>
		<div class="pageHeader">
			<div class="container">
				<h2><?=$lang->translate($title, "tool-name");?></h2>
			</div>
		</div>

		<div class="container">
			<div class="row">
				<div class="col-lg-12 col-md-12 col-sm-12">
					<div class="well well-lg">
						<ul>
							<?php
								foreach($words as $word) {
							?>
							<li><?=$word;?></li>
							<?php
								}
							?>
						</ul>
					</div>
				</div>
			</div>

			<a href="<?=$path;?>./tools.php" class="btn btn-lg btn-success"><?=$lang->translate("Browse More Tools", "button");?></a>
			&nbsp;
			<a href="" class="btn btn-lg btn-danger"><?=$lang->translate("Check Another Website", "button");?></a>
		</div>