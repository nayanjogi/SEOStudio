<?php
	$saved = false;
	$error = false;
	
	if(isset($_POST['doFormPost'])) {
		foreach($_POST as $i=>$v) {
			if($i != "doFormPost") {
				$db->write(str_replace('_', " ", $i), $v);
			}
		}
		$saved = true;
	}
?>

<?php if($saved) { ?><div class="alert alert-success">
			<strong>Awesome!</strong> Changes have been saved successfully.
		</div><?php } elseif($error !== false) { ?><div class="alert alert-error">
			<strong>Error!</strong> <?=$error;?>
		</div><?php } ?>

<div class="bs-callout bs-callout-danger">
      <h4>We don't recommend using this!</h4>
      <p>Throughout their history, meta tags have been abused. Most search engines do not use them any more. SEO Studio was built in a way that
      search engines can understand the content, so we do not recommend using these.</p>
   </div>

<form id="mainForm" action="" method="post">
	<input type="hidden" name="doFormPost" value="true" />
	
	<div class="moduleBox">
		<div class="moduleBoxTitle">
			<strong>Meta Tags</strong>
		</div>
		<div class="moduleBoxBody">
			<div class="form-horizontal">
				<div class="form-group">
					<label class="col-lg-2 control-label">Keywords</label>
					<div class="col-lg-10">
						<input type="text" name="Meta Keywords" class="form-control" value="<?=$db->read('Meta Keywords');?>" />
					</div>
				</div>
				<div class="form-group">
					<label class="col-lg-2 control-label">Description</label>
					<div class="col-lg-10">
						<textarea name="Meta Description" value="" class="form-control" style="height: 100px;"><?=$db->read('Meta Description');?></textarea>
					</div>
				</div>
				<div class="form-group">
					<div class="col-lg-10 col-lg-offset-2">
						<img id="SaveLoading" src="../img/Windows8Loader.gif" alt="Loading" style="display: none;">
						<input id="SaveButton" type="submit" name="" value="Save Changes" class="btn btn-info" />
					</div>
				</div>
			</div>
		</div>
	</div>
	
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
		.MainFrame tr td tr {
			vertical-align: middle !important;
		}
	</style>