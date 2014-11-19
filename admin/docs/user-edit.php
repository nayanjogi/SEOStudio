<?php
	$username = $_GET['username'];
	$userRow = null;

	$users = file_get_contents("../data/users.json");
	$users = json_decode($users, true);

	$userId = 0;

	foreach($users as $i=>$userRow2) {
		if(strtolower($userRow2['Username']) == strtolower($username)) {
			$userRow = $userRow2;
			$userId = $i;
		}
	}

	if($userRow == null) {
		header("Location: index.php?page=users");
		exit;
	}

	$error222 = '';

	if(isset($_POST['username'])) {
		$username = $_POST['username'];
		$email = $_POST['email'];
		$group = $_POST['group'];

		if(ctype_alnum($username)) {
			$nameTaken = false;

			foreach($users as $i=>$userRow2) {
				if(strtolower($userRow2['Username']) == strtolower($username)) {
					if($i != $userId) {
						$nameTaken = true;
					}
				}
			}

			if(!$nameTaken) {

			$users[$userId]['Username'] = $username;
			$users[$userId]['Email'] = $email;
			$users[$userId]['Group'] = $group;

			if($_POST['newpass'] != "") {
				$users[$userId]['Password'] = crypt($_POST['newpass']);
			}

			file_put_contents("../data/users.json", json_encode($users));

			header("Location: index.php?page=users");
			exit;

			}
			else {
				$error222 = "That name is already being used.";
			}
		}
		else {
			$error222 = "Only alphanumeric usernames are allowed.";
		}
	}

	if(!isset($userRow['Email'])) $userRow['Email'] = "";
?>
<div style="padding-left: 20px;">

	<h3>Edit User: <?=$userRow['Username'];?></h3>
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
					<th>New Password (optional)</th>
					<th width="120px">Actions</th>
				</tr>
			</thead>
			<tbody>
				<tr>
					<td>
						<input type="text" class="form-control" name="username" value="<?=$userRow['Username'];?>">
					</td>
					<td>
						<input type="text" class="form-control" name="email" value="<?=$userRow['Email'];?>">
					</td>
					<td>
						<select name="group" class="form-control">
							<?php
								$groups = file_get_contents("../data/groups.json");
								$groups = json_decode($groups, true);

								foreach($groups as $group) {
									if($group['Name'] != "public") {
							?>
							<option <?php if($group['Name'] == $userRow['Group']) { ?>selected <?php } ?>value="<?=$group['Name'];?>"><?=$group['Name'];?></option>
							<?php
									}
								}
							?>
						</select>
					</td>
					<td>
						<input type="password" class="form-control" name="newpass" value="">
					</td>
					<td>
						<input type='submit' class="btn btn-info btn-sm" value="Save" />
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