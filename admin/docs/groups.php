<div style="padding-left: 20px;">
	<div class="pull-right" style="padding-top: 8px;">
		<a href="?page=new-group" class="btn btn-md btn-success">Add New</a>
	</div>
	<h3>Groups</h3>
	<hr />

	<table width="100%" class="table table-striped table-hover">
		<thead>
			<tr>
				<th>Group</th>
				<th>Disabled Tools</th>
				<th width="100px">Accounts</th>
				<th width="300px">Actions</th>
			</tr>
		</thead>
		<tbody>
	<?php
		$groups = file_get_contents("../data/groups.json");
		$groups = json_decode($groups, true);

		foreach($groups as $groupRow) {
			$users = file_get_contents("../data/users.json");
			$users = json_decode($users, true);

			$num_accounts = 0;

			foreach($users as $userRow) {
				if($userRow['Group'] == $groupRow['Name']) $num_accounts++;
			}
	?>
			<tr>
				<td width="200px">
					<?=$groupRow['Name'];?>
					<?php if($groupRow['Name'] == $db->read("Default Group")) { ?>(default)<?php } ?>
				</td>
				<td>
					<?=implode(", ", $groupRow['Disallow']);?>
					<?php if(count($groupRow['Disallow']) == 0) { echo "None"; } ?>
				</td>
				<td>
					<?php if($groupRow['Name'] != "public") { ?><?=number_format($num_accounts);?><?php } else { ?>-<?php } ?>
				</td>
				<td>
					<a href="?page=group-edit&name=<?=$groupRow['Name'];?>" class="btn btn-info btn-sm">Edit</a>
					<a href="?page=group-remove&name=<?=$groupRow['Name'];?>" class="btn btn-danger btn-sm">Remove</a>
					<?php if($db->read("Default Group") == $groupRow['Name'] || $groupRow['Name'] == "public") { ?>
						<a href="#" class="btn btn-success btn-sm" disabled>Make Default</a>
					<?php } else { ?>
						<a href="?page=group-default&name=<?=$groupRow['Name'];?>" class="btn btn-success btn-sm">Make Default</a>
					<?php } ?>
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

<?php
	require "i.footer.php";
?>