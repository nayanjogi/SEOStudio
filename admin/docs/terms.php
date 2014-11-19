<?php
	$error = false;
	$saved = false;
	
	if(isset($_POST['doFormPostback'])) {
		sleep(2);
		
		$tos = $_POST['documentTerms'];
		
		$f1 = file_put_contents("../data/tos.html", $tos);
		
		if($f1 === false) {
			$error = "One of the files in /data/ is not writeable.";
		}
		else {
			$saved = true;
		}
	}
?>

<script src="ckeditor/ckeditor.js"></script>
<script src="ckeditor/adapters/jquery.js"></script>
	
	<!-- Begin Error -->
	
	<?php if($saved) { ?><div class="alert alert-success">
		<strong>Awesome!</strong> Changes have been saved successfully.
	</div><?php } elseif($error !== false) { ?><div class="alert alert-error">
		<strong>Error!</strong> <?=$error;?>
	</div><?php } ?>
	
	<!-- End Error -->
	
<form id="mainForm" action="" method="post" onsubmit="">
	<input type="hidden" name="doFormPostback" value="1"> 
	
	<div class="panel panel-info">
		<div class="panel-heading">
			<h3 class="panel-title" style="font-size: 14px; padding: 5px;">Terms of Usage</h3>
		</div>
		<div class="panel-body" style="padding: 0px;">
			<textarea name="documentTerms" id="tosEditor"><?=file_get_contents("../data/tos.html");?></textarea>
		</div>
	</div>
	
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
	
	<script>
		$( document ).ready( function() {
			$( 'textarea#tosEditor' ).ckeditor({
				uiColor: '#ffffff'
			});
			$( 'textarea#ppEditor' ).ckeditor({
				uiColor: '#ffffff'
			});
		} );
	</script>