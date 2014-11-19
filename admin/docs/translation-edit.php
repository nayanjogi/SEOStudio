<?php
	$id = $_GET['id'];
	
	if(!file_exists("../data/lang." . strtolower($id) . ".json")) {
		exit("<h4>Corrupt Language System</h4> The language system has gone corrupt. This is the result of deleted files. Please contact <a href='http://www.webfector.com/support/new-ticket'>Webfector Support</a> to get this fixed asap.<br /><br />Service to your website should remain fine, as long as the default language file is still in-tact.");
	}
	
	// Get the language set //
	
	$languageFile = "../data/lang." . (strtolower($id)) . ".json";
	$language = json_decode(file_get_contents($languageFile), true);
	
	$newLanguage = array();
	// Proceeding! //
	
	if(isset($_POST['doFormPostback'])) {
		foreach($_POST as $i=>$v) {
			if($i != "doFormPostback") {
				
				$languagePartName = "";
				$languagePartValue = trim($v);
				
				foreach($language as $lang=>$val) {
					if(md5(trim(strtolower(str_replace(array("'", '"'), "", $lang)))) == $i) {
						$newLanguage[$lang] = array(stripslashes($languagePartValue), $val[1]);
					}
				}
			}
		}
		
		$languageFileName = strtolower($id);
		
		$newLanguageFile = json_encode($newLanguage);
		
		file_put_contents("../data/lang.{$languageFileName}.json.backup", file_get_contents("../data/lang.{$languageFileName}.json"));
		file_put_contents("../data/lang.{$languageFileName}.json", $newLanguageFile);
		
		header("Location: ?page=translations");
		exit;
	}

	$lang = array(
		'Misc' => array(),
		'Variables' => array(),
		'Headers' => array(),
		'Footer' => array(),
		'Table Headers' => array(),
		'Tool Names' => array(),
		'Buttons' => array(),
		'Home Page' => array(),
		'Tool Descriptions' => array(),
		'Statuses' => array(),
		'Categories' => array(),
		'Results' => array(),
		'Inputs' => array(),
		'Units' => array(),
		'Formats' => array(),
		'Labels' => array(),
		'Errors' => array(),
		'Titles' => array()
	);
	$catTrans = array(
		'variable' => "Variables",
		'header' => "Headers",
		'title' => "Titles",
		'footer' => "Footer",
		'thead' => "Table Headers",
		'tool-name' => "Tool Names",
		'category' => "Categories",
		'button' => "Buttons",
		'tool-description' => "Tool Descriptions",
		'status' => "Statuses",
		'results' => "Results",
		'input' => "Inputs",
		'home' => "Home Page",
		'head' => "Home Page",
		'unit' => "Units",
		'format' => "Formats",
		'error' => "Errors",
		'label' => "Labels",
	);

	foreach($language as $phrase => $arr) {
		$cat = $arr[1];
		if($cat == '') {
			$lang['Misc'][$phrase] = $arr;
		}
		else {
			if(isset($catTrans[$cat])) {
				$lang[$catTrans[$cat]][$phrase] = $arr;
			}
			else {
				if(substr($cat, 0, 1) == "@") {
					$cat = str_replace("@", "", $cat);
					if(!isset($lang[$cat])) {
						$lang[$cat] = array();
					}

					$lang[$cat][$phrase] = $arr;
				}
				else {
					$lang['Misc'][$phrase] = $arr;
				}
			}
		}
	}

?>

<div style="padding-left: 15px;">
<div class="page-header" style="background: #ddd; margin-bottom: 20px; padding: 15px 25px 10px; margin-top: 15px;">
	<div class="pull-right">
		<a href="?page=translations" class="btn btn-danger btn-sm btn-tooltip" data-placement="left" title="Go Back">Cancel</a>
	</div>
	
	<h4>Edit Translation: <?=ucfirst($id);?></h4>
</div>

<form class="form-horizontal" method="POST" action="" role="form">
	<input type="hidden" name="doFormPostback" value="1">
	
	<?php
		foreach($lang as $category => $terms) { 
	?>
		<h4><?=$category;?></h4>
		<hr />

		<table class="table table-striped" style="margin-bottom: 40px;">
			<tbody>
				<?php
					foreach($terms as $phrase => $arr) { 
						$newphrase = $arr[0];
						$newPhraseForValue = trim(str_replace('"', '\"', $newphrase));
						$phraseForName = trim(str_replace('"', '\"', str_replace(' ', '_', $phrase)));
				?>
				<tr>
					<td width='50%'>
						<b>"<?=$phrase;?>"</b>
					</td>
					<td>
						<input type="text" class="form-control input-block" name="<?=md5(trim(strtolower(str_replace(array("'", '"'), "", $phrase))));?>" value="<?=$newPhraseForValue;?>" />
					</td>
				</tr>
				<?php
					}
				?>
			</tbody>
		</table>	
	<?php
		}
	?>
	<hr />
	<div class="form-actions">
		<img id="SaveLoading" src="../img/Windows8Loader.gif" alt="Loading" style="display: none;">
		<input id="SaveButton" type="submit" name="" value="Save Changes" class="btn btn-info" />
	</div>
</form>

	<script>
		$("#SaveButton").click(function() {
			$("#SaveLoading").show();
			$("#SaveButton").hide();
		});
	</script>
	
	<style>
		input[type=text] {
			margin-bottom: 0;
		}
		select {
			margin-bottom: 0;
		}
		tr td tr {
			vertical-align: middle !important;
		}
	</style>
</div>