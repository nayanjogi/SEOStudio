<?php
	$website = $application->getInput("website");
	$search = $application->getInput("search");

	$website = str_replace(array("http://", "https://", "www."), "", $website);
	if(strpos($website, "/") !== false) {
		$website = substr($website, 0, strpos($website, "/"));
	}

	$found = false;
	$rank = 0;
	$start = -30;
	$max_start = 500;

	while(!$found && $start < $max_start) {
		$start = $start + 30;

		$googleResult1 = file_get_html("https://www.google.com/search?q=" . urlencode($search) . "&hl=en&ie=UTF-8&btnG=Google+Search&num=30&start={$start}");
		$linkQuery = $googleResult1->find("cite");
		
		foreach($linkQuery as $x) {
			if(!$found) {
				$link = $x->plaintext;
				$rank++;

				$link = str_replace("www.", "", $link);
				if(strpos($link, "/") !== false) {
					$link = substr($link, 0, strpos($link, "/"));
				}
                
                if(isset($link) && isset($website) && $link != "" && $website != "") {
                    if(strpos($link, $website) !== false) {
                        $found = true;
                    }
                }
			}
		}
	}

	if($found) {
		$phrase = $lang->translate("The website <b>%1</b> has a rank of <b>%2</b> with the keywords provided.", 'variable');
		$phrase = str_replace("%1", "$website", $phrase);
		$phrase = str_replace("%2", "$rank", $phrase);
	}
	else {
		$phrase = $lang->translate("The website <b>%1</b> was not found on the first few hundred results.", 'variable');
		$phrase = str_replace("%1", "$website", $phrase);
	}
?>
		<div class="pageHeader">
			<div class="container">
				<h2><?=$lang->translate($title, "tool-name");?></h2>
			</div>
		</div>

		<div class="container">
			<p><?=$phrase?></p>

			<hr />
			
			<a href="<?=$path;?>./tools.php" class="btn btn-lg btn-success"><?=$lang->translate("Browse More Tools", "button");?></a>
			&nbsp;
			<a href="" class="btn btn-lg btn-danger"><?=$lang->translate("Check Another Website", "button");?></a>
		</div>