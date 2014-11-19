<?php
	$id = ucfirst($_GET['id']);
	if($id == "English") exit("Cannot delete built-in language file. Deleting it will make you unable to create new languages.");
	
	if(isset($_GET['confirm'])) {
		$newLanguageDb = array();
		$languageDb = $db->read("Languages");
		
		foreach($languageDb as $language) {
			if(strtolower($language) != strtolower($id)) {
				$newLanguageDb[] = ucfirst($language);
			}
		}
		
		// The .json file for the language will be kept as .json.backup
		
		$langName = strtolower($id);
		
		if(file_exists("../data/lang.{$langName}.json")) {
			file_put_contents("../data/lang.{$langName}.json.backup", file_get_contents("../data/lang.{$langName}.json"));
			unlink("../data/lang.{$langName}.json");
		}
		
		$db->write("Languages", $newLanguageDb);
		
		if($db->read("Default Language") == $id) {
			$db->write("Default Language", $newLanguageDb[0]);
		}
		
		header("Location: ?page=translations");
		exit;
	}
?>

<div style="padding-left: 15px;">
<div class="page-header" style="margin-top: 15px;">
		<div class="pull-right">
			<a href="?page=translations" class="btn btn-danger btn-sm btn-tooltip" data-placement="left" title="Go Back">Cancel</a>
		</div>
		
		<h4>Deletion Confirmation</h4>
	</div>

	<p>You are requesting to delete the language file for <strong><?=$id;?></strong>. Are you sure you want to do this?</p>
	<hr />
	
	<a href="?page=translation-delete&id=<?=$id;?>&confirm=1" class="btn btn-success">Yes, delete the language file</a>
	<a href="?page=translations" class="btn btn-danger">No, do not delete it</a>
	
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