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

	
<form id="mainForm" action="" method="post">
	<input type="hidden" name="doFormPost" value="true" />

	<div class="row">
		<div class="col-md-9">
			<div class="moduleBox">
				<div class="moduleBoxTitle">
					<strong>Designer</strong>
				</div>
				<div class="moduleBoxBody">
					<div class="HomePageBuilder">
						<div class="HomePageBuilderElement ElementHeader">
							Navigation Bar
						</div>
						<div class="HomePageBuilderElement ElementBody">
							<table width="100%">
								<td class="HomePageBuilderLeft">
									<input type="text" name="Home_Page_Head" value="<?=$db->read('Home Page Head');?>">
									<textarea name="Home_Page_Body"><?=$db->read('Home Page Body');?></textarea>
								</td>
								<td class="HomePageBuilderRight" width="35%">
									<div class="ImageContainer" style="position: relative;">
										<input type="text" id="Home_Banner_360x200" name="Home_Banner_360x200" value="<?=$db->read('Home Banner 360x200');?>" style="width: 100%; display: none; position: absolute; top: 10px;">
										<a href="javascript:;" class="Home_Banner_360x200" onclick="EditImage('Home_Banner_360x200');" style="color: #000; position: absolute; top: 10px; right: 10px;"><span class="glyphicon glyphicon-pencil"></span></a>
										<img src="<?=$db->read('Home Banner 360x200');?>" width="100%">
									</div>
								</td>
							</table>
						</div>
						<div class="HomePageBuilderElement ElementCallToAction">
							<input type="text" name="Home_Page_Sub" value="<?=$db->read('Home Page Sub');?>">
						</div>
					</div>
				</div>
			</div>

			<div class="bs-callout bs-callout-danger" style="margin-top: 0px;">
			      <h4>Want to change the home page title?</h4>
			      <p>You can change the title of the home page per-translation in the Translations page. Look for the Titles category.</p>
			</div>
		</div>
		<div class="col-md-3">
			<div class="moduleBox">
				<div class="moduleBoxTitle">
					<strong>Options</strong>
				</div>
				<div class="moduleBoxBody">
					<input type="submit" class="btn btn-success btn-block" value="Save Changes">
				</div>
			</div>
			<div class="moduleBox">
				<div class="moduleBoxTitle">
					<strong>Instructions</strong>
				</div>
				<div class="moduleBoxBody">
					Click on a text box to change the text. To change an image, click the Pencil icon overlaying the image and supply a new URL.
				</div>
			</div>
			<div class="moduleBox">
				<div class="moduleBoxTitle">
					<strong>Free File Hosting</strong>
				</div>
				<div class="moduleBoxBody">
					<iframe style="width: 100%; height: 180px; border:0px;" frameBorder="0" src="http://www.altusia.com/service/file_hosting/upload.php?ref=seo-studio"></iframe>
				</div>
			</div>
		</div>
	</div>
</form>


<script>
	function EditImage(imgName) {
		$("." + imgName).hide();
		$("#" + imgName).fadeIn();
	}
</script>