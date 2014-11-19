<?php
	if(isset($_POST['doFormPostback'])) {
		$name = trim($_POST['name']);
		$default = $_POST['default'];
		
		$languages = $db->read("Languages");
		if($languages[ucfirst($name)]) exit("This language already exists in the language database.");
		$languages[] = ucfirst($name);
		
		$db->write("Languages", $languages);
		
		if($default == "Enabled") $db->write("Default Language", ucfirst($name));
		file_put_contents("../data/lang." . strtolower($name) . ".json", file_get_contents("../data/lang.english.json"));
		
		header("Location: ?page=translation-edit&id=".ucfirst($name));
		exit;
	}
?>
<div style="padding-left: 15px;">
<div class="page-header" style="margin-top: 15px;">
	<div class="pull-right">
		<a href="?page=translations" class="btn btn-danger btn-sm btn-tooltip" data-placement="left" title="Go Back">Cancel</a>
	</div>
	
	<h4>Add New Translation</h4>
</div>

<form class="form-horizontal" method="POST" action="" role="form">
	<input type="hidden" name="doFormPostback" value="1">
	
  <div class="form-group">
    <label for="inputEmail3" class="col-sm-2 control-label">Language Name</label>
    <div class="col-sm-10">
      <input type="text" name="name" class="form-control" id="inputEmail3" placeholder="English">
    </div>
  </div>
  <div class="form-group">
    <label for="inputEmail4" class="col-sm-2 control-label">Default Settings</label>
    <div class="col-sm-10">
      <select name="default" class="form-control" id="inputEmail4">
		<option value="Disabled">Do not make this the default language (can be changed)</option>
		<option value="Enabled">Make this the default language (can be changed)</option>
	  </select>
    </div>
  </div>
  <div class="form-group">
    <div class="col-sm-offset-2 col-sm-10">
      <button type="submit" class="btn btn-default">Create Language</button> &nbsp; &nbsp; <span style="color: gray; font-size: 12px;">(You will be redirected to the translation page.)</span>
    </div>
  </div>
</form>

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