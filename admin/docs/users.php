<div style="padding-left: 20px;">
	<div class="pull-right" style="padding-top: 8px;">
		<a href="?page=new-user" class="btn btn-md btn-success">Add New</a>
	</div>
	<h3>Users</h3>
	<hr />

	<table width="100%" class="table table-striped table-hover">
		<thead>
			<tr>
				<th>Username</th>
				<th>Email</th>
				<th>Group</th>
				<th>IP</th>
				<th width="300px">Actions</th>
			</tr>
		</thead>
		<tbody>
	<?php
		$users = file_get_contents("../data/users.json");
		$users = json_decode($users, true);

		foreach($users as $userRow) {
			if(!isset($userRow['Email'])) $userRow['Email'] = '-';
	?>
			<tr>
				<td>
					<a href="?page=user-profile&username=<?=$userRow['Username'];?>"><?=$userRow['Username'];?></a>
				</td>
				<td>
					<?=$userRow['Email'];?>
				</td>
				<td>
					<?=$userRow['Group'];?>
				</td>
				<td>
					<?=$userRow['Address'];?>
				</td>
				<td>
					<a href="?page=user-edit&username=<?=$userRow['Username'];?>" class="btn btn-info btn-sm">Edit</a>
					<a href="?page=user-remove&username=<?=$userRow['Username'];?>" class="btn btn-danger btn-sm">Remove</a>
				</td>
			</tr>
	<?php
		}
	?>
		</tbody>
	</table>

	<style>
		tr td tr {
			vertical-align: middle !important;
		}
	</style>
</div>