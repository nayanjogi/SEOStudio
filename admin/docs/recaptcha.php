<?php
	$saved = false;
	$error = false;

	if(isset($_GET['activate'])) {
		$db->write("Captcha", "On");
	}
	if(isset($_GET['deactivate'])) {
		$db->write("Captcha", "Off");
	}
	
	if(isset($_POST['doFormPost'])) {
		foreach($_POST as $i=>$v) {
			if($i != "doFormPost") {
				if(!$v) $v = "X";
				$db->write(str_replace('_', " ", $i), $v);
			}
		}
		$saved = true;
	}

	if($db->read("Captcha") !== "On") {
?>

<div style="background: #eee; padding: 30px; border: 1px solid #ddd;">
	<strong>ReCAPTCHA is currently disabled.</strong>
	&nbsp; &nbsp; &nbsp; 
	<a href="?page=recaptcha&activate=on" class="btn btn-success btn-md">Activate</a>
</div>

<?php
	}
	else {
?>

<div style="padding-left: 20px;">
	<div class="page-header">
		<h3 style="line-height: 30px; margin-top: -10px;">Manage Captcha</h3>
		<p>Change the Captcha settings for your website to prevent bots, scrapers, or other automated devices from misusing the website.</p>
	</div>
	
	<form action="" method="POST">
		<input type="hidden" name="doFormPost" value="true" />
		
		<?php if($saved) { ?><div class="alert alert-success">
			<strong>Awesome!</strong> Changes have been saved successfully.
		</div><?php } elseif($error !== false) { ?><div class="alert alert-error">
			<strong>Error!</strong> <?=$error;?>
		</div><?php } ?>
		
		
		
		<table class="table table-striped">
			<tbody>
				<tr>
					<td width='270'>
						<b>Disable Google Captcha</b>
					</td>
					<td>
						<a href="?page=recaptcha&deactivate=on" class="btn btn-danger">Deactivate</a>
					</td>
					<td>Click to disable ReCAPTCHA.</td>
				</tr>
				<tr>
					<td>
						<b>Captcha Public Key</b>
					</td>
					<td>
						<input type="text" name="Captcha Public Key" class="form-control" value="<?=$db->read('Captcha Public Key');?>" />
					</td>
					<td>You can get a public key from the official <a href="https://www.google.com/recaptcha/intro/index.html">captcha site</a>.</td>
				</tr>
				<tr>
					<td>
						<b>Captcha Private Key</b>
					</td>
					<td>
						<input type="text" name="Captcha Private Key" class="form-control" value="<?=$db->read('Captcha Private Key');?>" />
					</td>
					<td>You can get a private key from the official <a href="https://www.google.com/recaptcha/intro/index.html">captcha site</a>.</td>
				</tr>
				<tr>
					<td>
						<b># Tools Before Captcha</b>
					</td>
					<td>
						<input type="text" name="Captcha Tools Before Activation" class="form-control" value="<?=str_replace('X', '0', $db->read('Captcha Tools Before Activation'));?>" style="width: 50px;" />
					</td>
					<td>
						How many tools can a user use before captcha activates? Set to '0' to require captcha <i>before</i> any tools can be used.
					</td>
				</tr>
				<tr>
					<td>
						<b># Tools After Captcha</b>
					</td>
					<td>
						<input type="text" name="Captcha Tools After Activation" class="form-control" value="<?=str_replace('X', '0', $db->read('Captcha Tools After Activation'));?>" style="width: 50px;" />
					</td>
					<td>
						After a user passes captcha the first time, how many tools can they use before they are required to pass captcha again? Set to '0' to disable.
					</td>
				</tr>
				<tr>
					<td></td>
					<Td><input type="submit" name="" value="Save Changes" class="btn btn-success" /></td>
					<td></td>
				</tr>
			</tbody>
		</table>
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

<?php
	}
?>