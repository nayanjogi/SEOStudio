<?php
	$saved = false;
	$error = false;
	
	if(isset($_POST['doFormPost'])) {
		foreach($_POST as $i=>$v) {
			if($i != "doFormPost") {
				$v = stripslashes($v);
				$db->write(str_replace('_', " ", $i), $v);
			}
		}
		$saved = true;
	}
?>

<div style="padding-left: 15px;">
	<div class="page-header" style="margin-top: 0px;">
		<h3 style="line-height: 30px;">Edit Ad Banners</h3>
		<p>Turn ads on or off, and enter the AdSense code for each of the ad sizes used throughout the website. To disable a specific ad banner, leave it blank.</p>
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
						<b>AdSense 728x90</b>
					</td>
					<td>
						<textarea name="Adsense 728x90" class="form-control" value="" style="width: 400px; height: 100px;"><?=$db->read('Adsense 728x90');?></textarea>
					</td>
				</tr>
				<tr>
					<td width='270'>
						<b>AdSense 160x600</b>
					</td>
					<td>
						<textarea name="Adsense 160x600" class="form-control" value="" style="width: 400px; height: 100px;"><?=$db->read('Adsense 160x600');?></textarea>
					</td>
				</tr>
				<tr>
					<td width='270'>
						<b>AdSense 300x250</b>
					</td>
					<td>
						<textarea name="Adsense 300x250" class="form-control" value="" style="width: 400px; height: 100px;"><?=$db->read('Adsense 300x250');?></textarea>
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
		.MainFrame tr td tr {
			vertical-align: middle !important;
		}
	</style>
</div>