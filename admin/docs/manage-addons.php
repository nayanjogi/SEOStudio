<?php
	if(isset($_GET['uninstall'])) {
		$uninstall = $_GET['uninstall'];

		$addons = file_get_contents("../data/addons.json");
		$addons = json_decode($addons, true);

		foreach($addons as $addonName=>$row) {
			if(md5($addonName) == $uninstall) {
				$uninstallScript = "../" . $row['Uninstall'];
				if(file_exists($uninstallScript)) {
					sleep(5);

					require_once $uninstallScript;
					if(function_exists("uninstall")) {
						$success = uninstall();

						if($success == false) {
							exit("Failed to uninstall for an unknown reason.");
						}
						else {
							header("Location: manage.addons.php");
						}
					}
				}
			}
		}
	}
?>

<div style="padding-left: 20px;">

	<h3>Manage Addons</h3>
	<hr />

	<table width="100%" class="table table-striped table-hover">
		<thead>
			<tr>
				<th>Name & Information</th>
				<th width="300px">Actions</th>
			</tr>
		</thead>
		<tbody>
	<?php
		$addons = file_get_contents("../data/addons.json");
		$addons = json_decode($addons, true);

		foreach($addons as $addonName=>$row) {
	?>
			<tr>
				<td>
					<div style="font-weight: bold; font-size: 13px; padding: 7px 10px;">
						<?=$addonName;?>
					</div>
					<div style="font-weight: normal; font-size: 13px; padding: 0px 10px 7px;">
						Version <?=$row['Version'];?> by <?=$row['Publisher'];?>. 
						Installed on <?=date("M d, Y", $row['Installed']);?> and last updated <?=date("M d, Y", $row['Updated']);?>.
					</div>
				</td>
				<td style="vertical-align: middle;">
					<a href="?uninstall=<?=md5($addonName);?>" class="btn btn-danger btn-sm">Uninstall</a>
					<a href="?configure=<?=md5($addonName);?>" class="btn btn-info btn-sm" disabled>Settings</a>
				</td>
			</tr>
	<?php
		}
	?>
		</tbody>
	</table>
</div>