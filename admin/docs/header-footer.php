<?php
	$error = false;
	$saved = false;
	
	if(isset($_POST['doFormPostback'])) {
		$brandMethod = $_POST['BrandMethod'];
		$db->write("Brand Method", $brandMethod);
		$db->write("Copyright", $_POST['Copyright']);
		
		if($brandMethod !== "Text" && $brandMethod !== "Image") exit;
		if($brandMethod == "Text") {
			$brandText = $_POST['ltext'];
			
			$db->write("Brand Name", $brandText);
			$saved = true;
		}
		elseif($brandMethod == "Image") {
			if($_FILES["file"]["error"] == 0) {
				$allowedExts = array("gif", "jpeg", "jpg", "png");
				$temp = explode(".", $_FILES["file"]["name"]);
				$extension = end($temp);
				if ((($_FILES["file"]["type"] == "image/gif") || ($_FILES["file"]["type"] == "image/jpeg")
				|| ($_FILES["file"]["type"] == "image/jpg") || ($_FILES["file"]["type"] == "image/pjpeg")
				|| ($_FILES["file"]["type"] == "image/x-png") || ($_FILES["file"]["type"] == "image/png"))
				&& ($_FILES["file"]["size"] < 15000000) && in_array($extension, $allowedExts)) {
					$move = move_uploaded_file($_FILES["file"]["tmp_name"], "../img/logo.$extension");
					if($move === false) {
						$error = "Could not upload to /img/logo.$extension.";
					}
					else {
						$db->write("Brand Image", "$extension");
						
						$saved = true;
					}
				}
				else {
					$error = "The file did not meet requirements. Must be a .png, .jpg, or a .gif.";
				}
			}
			else {
				
			}
		}
		
	}
?>

	<!-- Begin Error -->
	
	<?php if($saved) { ?><div class="alert alert-success">
		<strong>Awesome!</strong> Changes have been saved successfully.
	</div><?php } elseif($error !== false) { ?><div class="alert alert-danger">
		<strong>Error!</strong> <?=$error;?>
	</div><?php } ?>
	
	<!-- End Error -->
	
	
<form id="mainForm" action="" method="post" enctype="multipart/form-data">
	<input type="hidden" name="doFormPostback" value="1">
	
	<div class="moduleBox">
		<div class="moduleBoxTitle">
			<strong>Header</strong>
		</div>
		<div class="moduleBoxBody">
			<div class="form-horizontal">
				<div class="form-group">
					<label class="col-lg-2 control-label">Brand Method</label>
					<div class="col-lg-10">
						<input onclick="UpdateTB();" type="radio" name="BrandMethod" value="Text" id="BrandMethod1" <?php if($db->read("Brand Method") != "Image") { ?>checked<?php } ?>><label for="BrandMethod1">&nbsp; Text Logo</label><br />
						<input onclick="UpdateTB();" type="radio" name="BrandMethod" value="Image" id="BrandMethod2" <?php if($db->read("Brand Method") == "Image") { ?>checked<?php } ?>><label for="BrandMethod2">&nbsp; Image Logo</label><br />
					</div>
				</div>
				<div class="form-group" id="ImageBranding"<?php if($db->read("Brand Method") != 'Image') { ?> style="display: none;"<?php } ?>>
					<label class="col-lg-2 control-label">Select Image</label>
					<div class="col-lg-10">
						<input type="file" name="file" class="form-control" id="file">
					</div>
				</div>
				<div class="form-group" id="TextBranding"<?php if($db->read("Brand Method") != '' && $db->read("Brand Method") == 'Image') { ?> style="display: none;"<?php } ?>>
					<label class="col-lg-2 control-label">Select Text</label>
					<div class="col-lg-10">
						<input type="text" name="ltext" class="form-control" value="<?=$db->read("Brand Name");?><?php if($db->read("Brand Name") == '') { ?><?=$db->read("Website Name");?><?php } ?>" placeholder="Enter Logo Text">
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="moduleBox">
		<div class="moduleBoxTitle">
			<strong>Footer</strong>
		</div>
		<div class="moduleBoxBody">
			<p>The Terms of Use and Privacy Policy links can be managed via the links on the sidebar.</p>
			<br />
			
			<div class="form-horizontal">
				<div class="form-group">
					<label class="col-lg-2 control-label">Copyright Text</label>
					<div class="col-lg-10">
						<input type="text" name="Copyright" value="<?=$db->read("Copyright");?>" placeholder="(C) Your Company Name 2013" class="form-control">
					</div>
				</div>
				<div class="form-group">
					<label class="col-lg-2 control-label">Copyright Date</label>
					<div class="col-lg-10">
						<input type="radio" name="CopyrightDateAuto" value="Auto" id="BrandMethod1a" <?php if($db->read("CopyrightDateAuto") != "Fixed") { ?>checked<?php } ?>><label for="BrandMethod1a">&nbsp; Automatically change the copyright year in the footer label above.</label><br />
						<input type="radio" name="CopyrightDataAuto" value="Fixed" id="BrandMethod2b" <?php if($db->read("CopyrightDateAuto") == "Fixed") { ?>checked<?php } ?>><label for="BrandMethod2b">&nbsp; Do not change the copyright year in the footer label above.</label><br />
					</div>
				</div>
			</div>
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
		.MainFrame tr td tr {
			vertical-align: middle !important;
		}
	</style>
	
	<script>
		function UpdateTB() {
			var selected = $('input[name=BrandMethod]:checked', '#mainForm').val();
			if(selected == 'Text') {
				$("#TextBranding").show();
				$("#ImageBranding").hide();
			}
			else if(selected == 'Image') {
				$("#TextBranding").hide();
				$("#ImageBranding").show();
			}
		}
	</script>