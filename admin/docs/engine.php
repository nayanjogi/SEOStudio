<?php
	$tld = file_get_contents('../data/engine.etr');

	if(isset($_POST['engine'])) {
		file_put_contents("../data/engine.etr", $_POST['engine']);
			
		header("Location: ?page=engine");
		exit;
	}

	require_once "../libraries/simple_html_dom.php";

	// let's test

	$domain = "www.webfector.com";
	$wwwLink = parse_url("http://".$domain);
	$wwwLink = str_replace("www.", "", $wwwLink['host']);

	$googleResult1 = file_get_html("http://www.google.{$tld}/search?q=" . urlencode('"'.$wwwLink.'"'));
	$googleResult2 = file_get_html("http://www.google.{$tld}/search?q=" . urlencode('"www.'.$wwwLink.'"'));
	
	if($googleResult1 === false) {
		$testResults = "CANNOT CONNECT";
	}
	else {
		$googleResults1 = $googleResult1->find('div[id=resultStats]', 0)->innertext;
		$googleResults1 = str_replace(array("About ", " results", ","), "", $googleResults1);
		$googleResults1 = preg_replace('/[^0-9]/', '', $googleResults1);
		
		$googleResults2 = $googleResult2->find('div[id=resultStats]', 0)->innertext;
		$googleResults2 = str_replace(array("About ", " results", ","), "", $googleResults2);
		$googleResults2 = preg_replace('/[^0-9]/', '', $googleResults2);
		
		$backlinks = $googleResults1 + $googleResults2;
		$backlinks = intval($backlinks);

		if(is_numeric($backlinks) && $backlinks > 0) {
			$testResults = "SUCCESS!";
		}
		else {
			$testResults = "FAILED!";
		}
	}
?>

<div class="row">
	<div class="col-md-8">
		<div class="moduleBox">
			<div class="moduleBoxTitle">
				<strong>Search Engine</strong>
			</div>
			<div class="moduleBoxBody">
				<p>
					SEO Studio uses Google to collect its results. By default, it utilizes google<strong>.com</strong> for its information.
					However, you might want to choose a different google, for example google.es for spanish.
				</p>
			</div>
		</div>
		<div class="moduleBox">
			<div class="moduleBoxTitle">
				<strong>Configure Engine</strong>
			</div>
			<div class="moduleBoxBody">
				<p>Note that we will automatically try to adjust to the new google engine you provide, but sometimes it may fail due to a change in formatting. Please test the engine after setting it.</p>

				<br />

				<form action="" method="POST">
					
					www.google.<input type="text" value="<?=file_get_contents('../data/engine.etr');?>" name="engine">
					
					<hr />
					
					<input type="submit" class="btn btn-primary" value="Save Engine">
				</form>
			</div>
		</div>
	</div>
	<div class="col-md-4">
		<div class="moduleBox">
			<div class="moduleBoxTitle">
				<strong>Test Engine</strong>
			</div>
			<div class="moduleBoxBody">
				Testing with <strong>google.<?=file_get_contents('../data/engine.etr');?></strong>...
				<br/><br/>

				<?=$testResults;?>
			</div>
		</div>
		<div class="moduleBox">
			<div class="moduleBoxTitle">
				<strong>Customer Support</strong>
			</div>
			<div class="moduleBoxBody">
				Email me from <a href="http://codecanyon.net/user/webfector">my profile page</a> for support.
			</div>
		</div>
	</div>
</div>