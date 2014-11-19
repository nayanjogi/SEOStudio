<?php
	if(isset($_GET['default'])) {
		$languageDefault = $_GET['default'];
		$db->write("Default Language", $languageDefault);
		
		header("Location: ?page=translations");
	}

	if(isset($_POST['0x32d1'])) {
		$translation = $_POST['translationid'];
		$friend = $_POST['friendlyname'];

		if(strlen($friend) < 2) {
			exit("Please enter a valid 'English version' for this language.");
		}

		$languageFile = "../data/lang." . (strtolower($translation)) . ".json";
		if(file_exists($languageFile)) {
			$contents = file_get_contents($languageFile);

			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL,"http://api.altusia.com/seo-studio/translation/upload.php");
			curl_setopt($ch, CURLOPT_POST, 1);
			curl_setopt($ch, CURLOPT_POSTFIELDS, array('id' => $translation, 'name' => $friend, 'log' => $contents));
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			$send = curl_exec($ch);

			exit("<strong>Submission sent!</strong> <br /><br />
				Thanks for helping out. We will review it within the next few weeks, and may consider using it as a free translation in future SEO Studio versions.");
		}
		else {
			exit("Could not find the right language file. It might be corrupt. Sorry!");
		}
	}
?>

<div style="padding-left: 15px;">
<div class="page-header" style="margin-top: 15px;">
	<div class="pull-right">
		<a href="?page=translation-new" class="btn btn-primary btn-sm btn-tooltip" data-placement="left" title="Add New Translation"><span class="glyphicon glyphicon-plus"></span></a>
	</div>
	
	<h4>Your Translations</h4>
</div>

<table class="table">
<?php
	foreach($db->read("Languages") as $lang) {
?>
	<tr>
		<td style="border-top: 0px;">
			<b><?=$lang;?></b> <?php if($db->read("Default Language") == $lang) { ?>(Default)<?php } ?>
		</td>
		<td style="border-top: 0px;">
			<a href="?page=translation-edit&id=<?=$lang;?>" class="btn btn-info">Edit</a>
			
			<?php if($lang == 'English') { ?><a href="#" class="btn btn-danger btn-disabled btn-tooltip" data-placement="top" title="You cannot delete this language file as it is built-in.">Delete</a><?php } else { ?>
			<a href="?page=translation-delete&id=<?=$lang;?>" class="btn btn-danger">Delete</a>
			<?php } ?>
			&nbsp;
			
			<?php if($lang != $db->read("Default Language")) { ?><a href="?page=translations&default=<?=$lang;?>" class="btn btn-default">Make Default</a><?php } ?>
		</td>
	</tr>
<?php
	}
?>
</table>

<div class="page-header" style="margin-top: 55px;">
	<h4>Help us out!</h4>
</div>

<p>
	We are collecting accurate translations to add to our library, in order to offer them to other SEO Studio users. 
	If you want to send us a copy of one of your translations to help out, you can do so below.
</p>

<form action="" method="POST">
	<table width="100%">
		<tr style="vertical-align: top !important;">
			<td width="30%">
				<div style="margin-top: 25px; background: #eee; border: 1px solid #ddd; padding: 20px;">
					<input type="hidden" name="0x32d1" value="<?=md5(rand());?>">

					<div style="padding-bottom: 5px;">Language to send:</div>
					<select name="translationid" class="form-control">
				<?php
					foreach($db->read("Languages") as $lang) {
				?>
						<option value="<?=$lang;?>"><?=$lang;?></option>
				<?php
					}
				?>
					</select>

					<div style="font-size: 12px; margin-top: 10px;"><i>
						Select which language you want to send to us. It will be reviewed and checked with other submissions.
					</i></div>
				</div>
			</td>
			<td width="3%">&nbsp;</td>
			<td width="30%">
				<div style="margin-top: 25px; background: #eee; border: 1px solid #ddd; padding: 20px;">
					<div style="padding-bottom: 5px;">English version of language name:</div>
					<input type="text" name="friendlyname" class="form-control">

					<div style="font-size: 12px; margin-top: 10px;"><i>
						What would you call the language, in English? For example, instead of "Espanol", say "Spanish".
					</i></div>
				</div>
			</td>
			<td width="3%">&nbsp;</td>
			<td width="30%">
				<div style="margin-top: 25px; background: #eee; border: 1px solid #ddd; padding: 20px;">
					<div style="padding-bottom: 5px;">Confirm submission:</div>
					<p style="font-size: 13px;">Once submitted, please do not resend the same translation unless you have made changes.</p>

					<input type='submit' class='btn btn-success' value="Submit">
				</div>
			</td>
		</tr>
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