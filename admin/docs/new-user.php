<?php
	$error222 = '';

	if(isset($_POST['username'])) {
		$username = $_POST['username'];
		$email = $_POST['email'];
		$group = $_POST['group'];
		$password = $_POST['pass'];

		if(ctype_alnum($username)) {
			if(strlen(trim($password)) > 2) {

				$usernameTaken = false;

				$users = file_get_contents("../data/users.json");
				$users = json_decode($users, true);
				foreach($users as $userRow2) {
					if(strtolower($userRow2['Username']) == strtolower($username)) {
						$usernameTaken = true;
					}
				}

				if(!$usernameTaken) {
					if(!$email) {
						$arr = array(
							'Username' => $username,
							'Group' => $group,
							'Password' => crypt($password),
							'Address' => ''
						);

						$users[] = $arr;

						file_put_contents("../data/users.json", json_encode($users));

						header("Location: index.php?page=users");
						exit;
					}
					else {
						$error222 = "Please enter an email address.";
					}
				}
				else {
					$error222 = "That username is not available.";
				}
			}
			else {
				$error222 = "Password is not good enough.";
			}
		}
		else {
			$error222 = "Only alphanumeric usernames are allowed.";
		}
	}
?>
<div style="padding-left: 20px;">
	<h3>Create User</h3>
	<hr />

	<?php if(isset($error222) && $error222 != '') { ?>

	<div class="alert alert-danger"><strong>Error:</strong> <?=$error222;?></div>

	<?php } ?>

	<form action="" method="POST">
		<table width="100%" class="table table-striped table-hover">
			<thead>
				<tr>
					<th>Alphanumeric Username</th>
					<th>Email</th>
					<th>Group</th>
					<th>Password</th>
					<th width="100px">Actions</th>
				</tr>
			</thead>
			<tbody>
				<tr>
					<td>
						<input type="text" class="form-control" name="username" value="">
					</td>
					<td>
						<input type="text" class="form-control" name="email" value="">
					</td>
					<td>
						<select name="group" class="form-control">
							<?php
								$groups = file_get_contents("../data/groups.json");
								$groups = json_decode($groups, true);

								foreach($groups as $group) {
									if($group['Name'] != "public") {
							?>
							<option value="<?=$group['Name'];?>"<?php if($group['Name'] == $db->read("Default Group")) { ?> selected<?php } ?>><?=$group['Name'];?></option>
							<?php
									}
								}
							?>
						</select>
					</td>
					<td>
						<input type="password" class="form-control" name="pass" value="">
					</td>
					<td>
						<input type='submit' class="btn btn-info btn-sm" value="Create" />
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