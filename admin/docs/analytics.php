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
	
<div style="padding-left: 15px;">
	<div class="page-header" style="margin-top: 0px;">
		<h3 style="line-height: 30px;">Edit Google Analytics</h3>
		<p>If you want to add Google Analytics code into your website, paste the code below.</p>
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
						<b>Analytics Code</b>
					</td>
					<td>
						<textarea name="Analytics" class="form-control" value="" style="width: 400px; height: 100px;"><?=$db->read('Analytics');?></textarea>
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