<?php
	if(isset($_POST['doFormPost'])) {
		$AllowRegistration = $_POST['AllowRegistration'];
		$HomeLoginReplacement = $_POST['hlreplace'];
		$CollectEmails = $_POST['emailcollect'];
		$VerifyEmails = $_POST['emailverify'];
		$HomeBox = $_POST['homebox'];
		$EmailFrom = $_POST['emailfrom'];
		$EmailFromName = $_POST['emailfromname'];

		if($AllowRegistration === "") $AllowRegistration = "On";

		if($HomeLoginReplacement == "keep") $HomeLoginReplacement = "1"; // keep on page
		if($HomeLoginReplacement == "replace1") $HomeLoginReplacement = "2"; // replace with random tools
		if($HomeLoginReplacement == "replace2") $HomeLoginReplacement = "3"; // remove and keep blank
		if($HomeLoginReplacement == "replace3") $HomeLoginReplacement = "4"; // html code (homebox)
		if(strlen($HomeLoginReplacement) > 1) $HomeLoginReplacement = "1";

		$db->write("Allow Registration", $AllowRegistration);
		$db->write("Home Login Replacement", $HomeLoginReplacement);
		$db->write("Collect Emails", $CollectEmails);
		$db->write("Verify Emails", $VerifyEmails);
		$db->write("EmailService Address", $EmailFrom);
		$db->write("EmailService Name", $EmailFromName);

		file_put_contents("../data/homebox.html", $HomeBox);

		$db->save();

		header("Location: ?page=registration&ok=1");
		exit;
	}
?>

<?php if(isset($_GET['ok'])) { ?>
<div class="alert alert-success"><strong>Changes saved!</strong> Your changes are now effective.</div>
<?php } ?>

<form action="" method="POST">
	<input type="hidden" name="AllowRegistration" value="" id="AllowRegistrationInput">
	<input type="hidden" name="doFormPost" value="1">

	<div class="row">
		<div class="col-md-9">
			<div class="moduleBox">
				<div class="moduleBoxTitle">
					<strong>User Registration</strong>
				</div>
				<div class="moduleBoxBody">

					<div class="pull-right"><div id="AllowRegistrationToggler" class="Toggler <?php if($db->read("Allow Registration") != "Off") { ?>ToggledOn<?php } else { ?>ToggledOff<?php } ?>">
						<div class="ToggleBeam"></div>
					</div></div>

					<p style="padding-top: 5px; padding-left: 15px;">Do you want to allow website visitors to register on this website?</p>

				</div>
			</div>
			<div class="moduleBox" id="fillerPanel">
				<div class="moduleBoxTitle">
					<strong>Home Page Filler</strong>
				</div>
				<div class="moduleBoxBody">

					<p>The homepage was designed with a login box. Some of our users want to change this box, so you can now choose if you want to replace it with something else.</p>

					<br />

					<label for="rd1">
						<input type="radio" name="hlreplace" value="keep" id="rd1"<?php if($db->read("Home Login Replacement") == "1" || $db->read("Home Login Replacement") === "") { ?> checked<?php } ?>>
						&nbsp;
						Keep the login box on the home page.
					</label><br />
					<label for="rd2">
						<input type="radio" name="hlreplace" value="replace1" id="rd2"<?php if($db->read("Home Login Replacement") == "2") { ?> checked<?php } ?>>
						&nbsp;
						Replace the login box with more random tools.
					</label><br />
					<label for="rd3">
						<input type="radio" name="hlreplace" value="replace2" id="rd3"<?php if($db->read("Home Login Replacement") == "3") { ?> checked<?php } ?>>
						&nbsp;
						Remove the login box and leave the space blank.
					</label><br />
					<label for="rd4">
						<input type="radio" name="hlreplace" value="replace3" id="rd4"<?php if($db->read("Home Login Replacement") == "4") { ?> checked<?php } ?>>
						&nbsp;
						Replace the login box with my own text or HTML code.
					</label>

				</div>
			</div>
			<div class="moduleBox" id="emailPanel" style="display: none;">
				<div class="moduleBoxTitle">
					<strong>User Emails</strong>
				</div>
				<div class="moduleBoxBody">
					<label for="rd5">
						<input type='hidden' value='Off' name='emailcollect'>
						<input type="checkbox" name="emailcollect" value="On" id="rd5"<?php if($db->read("Collect Emails") !== "Off") { ?> checked<?php } ?>>
						&nbsp;
						Require emails from users when they signup.
					</label><br />
					<label for="rd6">
						<input type='hidden' value='Off' name='emailverify'>
						<input type="checkbox" name="emailverify" value="On" id="rd6"<?php if($db->read("Verify Emails") !== "Off") { ?> checked<?php } ?>>
						&nbsp;
						Require users to verify their emails before they can use the website.
					</label>
				</div>
			</div>
			<div class="moduleBox" id="boxDesigner"<?php if($db->read("Home Login Replacement") !== "4") { ?> style="display: none;"<?php } ?>>
				<div class="moduleBoxTitle">
					<strong>Homepage Login Box Replacement</strong>
				</div>
				<div class="moduleBoxBody">
					<textarea name="homebox" id="homeboxEditor"><?=file_get_contents("../data/homebox.html");?></textarea>
				</div>
			</div>
			<div class="moduleBox" id="emailPanel">
				<div class="moduleBoxTitle">
					<strong>Email Service</strong>
				</div>
				<div class="moduleBoxBody">
					<p>Select the method which the application will use to send emails to your clients (for email verification and pass resets).</p>

					<table>
						<tr style='vertical-align: middle;'>
							<td style="padding-right: 20px;">What <b>email</b> will the mail be sent from?</td>
							<td width='300px'><input type="text" name="emailfrom" value="<?=$db->read('EmailService Address');?>" class="form-control" style="margin-bottom: 6px;"></td>
							<td style="padding-left: 15px;"><div style="font-size: 12px;"><i>Example: studio@seostudio.com</i></div></td>
						</tr>
						<tr style='vertical-align: middle;'>
							<td style="padding-right: 20px;">What <b>name</b> will the mail be sent from?</td>
							<td width='300px'><input type="text" name="emailfromname" value="<?=$db->read('EmailService Name');?>" class="form-control"></td>
							<td style="padding-left: 15px;"><div style="font-size: 12px;"><i>Example: SEO Studio</i></div></td>
						</tr>
					</table>
				</div>
			</div>
		</div>
	</div>

	<input type="submit" class="btn btn-lg btn-success" value="Save Changes">
</form>

<script src="ckeditor/ckeditor.js"></script>
<script src="ckeditor/adapters/jquery.js"></script>

<script>

	var UserRegistrationBeamOn = false;

	$(document).ready(function() {
		var UserRegistrationBeamClass = $("#AllowRegistrationToggler").attr('class');

		if(UserRegistrationBeamClass.indexOf("ToggledOn") != -1) {
			$(".ToggleBeam").html("ON");
			UserRegistrationBeamOn = true;
			$("#AllowRegistrationInput").val("On");
			$("#emailPanel").show()
		}
		else {
			$(".ToggleBeam").html("OFF");
			$("#AllowRegistrationInput").val("Off");
			$("#emailPanel").hide();
		}

		$( 'textarea#homeboxEditor' ).ckeditor({
			uiColor: '#ffffff'
		});
	});

	$("#AllowRegistrationToggler").click(function() {
		if(UserRegistrationBeamOn) {
			UserRegistrationBeamOn = false;

			$(".ToggleBeam").html("OFF");
			$("#AllowRegistrationToggler").attr("class", "Toggler ToggledOff");
			$("#AllowRegistrationInput").val("Off");
			$("#emailPanel").hide();
		}
		else {
			UserRegistrationBeamOn = true;
			$(".ToggleBeam").html("ON");
			$("#AllowRegistrationToggler").attr("class", "Toggler ToggledOn");
			$("#AllowRegistrationInput").val("On");
			$("#emailPanel").show()

		}
	});

	$("#rd4").change(function() {
		if($(this).is(':checked')) {
			$("#boxDesigner").show()
		}
		else {
			$("#boxDesigner").hide();
		}
	});
	$("#rd3").change(function() {
		if($(this).is(':checked')) {
			$("#boxDesigner").hide()
		}
	});
	$("#rd2").change(function() {
		if($(this).is(':checked')) {
			$("#boxDesigner").hide()
		}
	});
	$("#rd1").change(function() {
		if($(this).is(':checked')) {
			$("#boxDesigner").hide()
		}
	});

</script>