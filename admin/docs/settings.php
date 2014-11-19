<?php
	$saved = false;
	$error = false;
	
	if(isset($_POST['doFormPost'])) {
		sleep(2);
		
		foreach($_POST as $i=>$v) {
			if($i != "doFormPost") {
				$db->write(str_replace('_', " ", $i), $v);
			}
		}
		
		$saved = true;
		
		if($db->read("Email Update Notifications") == "Off") {
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, "http://api.webfector.com/public/76a3abd/subscription.php?token=" . $db->read("Token") . "&s=no&email=" . $db->read("Admin Email"));
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
			curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 15);
			curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
			curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.13) Gecko/20080311 Firefox/2.0.0.13');
			$data = curl_exec($ch);
		}
		else {
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, "http://api.webfector.com/public/76a3abd/subscription.php?token=" . $db->read("Token") . "&s=yes&email=" . $db->read("Admin Email"));
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
			curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 15);
			curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
			curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.13) Gecko/20080311 Firefox/2.0.0.13');
			$data = curl_exec($ch);
		}
		
	}
?>
	
	<form action="" method="POST">
		<input type="hidden" name="doFormPost" value="true" />
		
		<?php if($saved) { ?><div class="alert alert-success">
			<strong>Awesome!</strong> Changes have been saved successfully.
		</div><?php } elseif($error !== false) { ?><div class="alert alert-error">
			<strong>Error!</strong> <?=$error;?>
		</div><?php } ?>

		<div class="configurationBigRow">
			<h4>Website Configuration</h4>
		</div>
		<div class="configurationRow">
			<table>
				<tr>
					<td>
						<div class="configLabel">
							<strong rel="tooltip" title="The direct URL of the application.">Website URL</strong>
						</div>
					</td>
					<td>
						<div class="configItem">
							<input type="text" class="form-control input-block" name="Website URL" placeholder="Website URL" value="<?=$db->read('Website URL');?>">
						</div>
					</td>
				</tr>
				<tr>
					<td>
						<div class="configLabel">
							<strong rel="tooltip" title="The name of this website, as will appear in place of the logo if you do not provide an image logo.">Website Name</strong>
						</div>
					</td>
					<td>
						<div class="configItem">
							<input type="text" class="form-control input-block" name="Website Name" placeholder="Website Name" value="<?=$db->read('Website Name');?>">
						</div>
					</td>
				</tr>
			</table>
		</div>

		<div class="configurationBigRow">
			<h4>Features</h4>
		</div>
		<div class="configurationRow">
			<table>
				<tr>
					<td>
						<div class="configLabel">
							<strong rel="tooltip" title="Shows the Admin button in the menu of the application.">Admin Button</strong>
						</div>
					</td>
					<td>
						<div class="configItem">
							<input type='hidden' value='Off' name='Admin Button'>
							<input type="checkbox" class="checkable" name="Admin Button" value="On" <?php if($db->read("Admin Button") != "Off") { ?>checked<?php } ?>>
						</div>
					</td>
				</tr>
			</table>
		</div>

		<div class="configurationBigRow">
			<h4>Subscription</h4>
		</div>
		<div class="configurationRow">
			<p>
				You can manage your subscription at the <a href="?page=updates">updates</a> page.
			</p>
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