<?php
	$saved = false;
	$error = false;
	
	if(isset($_POST['doFormPost'])) {
		$email = $_POST['email'];
		$pass = $_POST['pass'];
		
		if($email == '') {
			$error = 'Invalid email. Cannot be blank.';
		}
		else {
			$db->write("Admin Email", $email);
			$saved = true;
			
			if($pass !== '') {
				$db->write("Admin Password", crypt($pass));
				$saved = true;
			}
		}
	}
?>
<div style="padding-left: 20px;">
	<h3>Admin Settings</h3>
	<hr />

	<?php if($saved) { ?><div class="alert alert-success">
			<strong>Awesome!</strong> Changes have been saved successfully.
		</div><?php } elseif($error !== false) { ?><div class="alert alert-error alert-danger">
			<strong>Error!</strong> <?=$error;?>
		</div><?php } ?>

	<form action="" method="POST">
		<input type="hidden" name="doFormPost" value="1">

		<table class="table table-striped">
			<tbody>
				<tr>
					<td width='150px'>
						<b>Email</b>
					</td>
					<td>
						<input type="text" name="email" class="form-control" value="<?=$db->read('Admin Email');?>" />
					</td>
				</tr>
				<tr>
					<td width='150px'>
						<b>New Password</b>
					</td>
					<td>
						<input type="password" name="pass" class="form-control" value="" />
					</td>
				</tr>
				<tr>
					<td>
						
					</td>
					<td>
						<input type="submit" name="go" value="Save Changes" class="btn btn-success" />
					</td>
				</tr>
			</tbody>
		</table>
	</form>

	<style>
		tr td tr {
			vertical-align: middle !important;
		}
	</style>
</div>