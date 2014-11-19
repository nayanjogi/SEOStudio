<?php
	$name = $_GET['name'];
	$groupRow = null;

	$groups = file_get_contents("../data/groups.json");
	$groups = json_decode($groups, true);

	$groupId = 0;

	foreach($groups as $i=>$groupRow2) {
		if(strtolower($groupRow2['Name']) == strtolower($name)) {
			$groupRow = $groupRow2;
			$groupId = $i;
		}
	}

	if($groupRow == null) {
		header("Location: index.php?page=groups");
		exit;
	}

	$error222 = '';

	if(isset($_POST['name'])) {
		$name = $_POST['name'];

		if($groupRow['Name'] == "public") {
			$name = 'public';
		}

		if(ctype_alnum($name)) {
			if($name != $groupRow['Name']) {
				// name change!

				$users = file_get_contents("../data/users.json");
				$users = json_decode($users, true);
				foreach($users as $i=>$userRow2) {
					if($userRow2['Group'] == $groupRow['Name']) {
						$users[$i]['Group'] = $name;
					}
				}

				file_put_contents("../data/users.json", json_encode($users));

				if($db->read("Default Group") == $groupRow['Name']) {
					$db->write("Default Group", $name);
				}
			}

			$newDisallow = array();

			foreach($_POST as $i=>$v) {
				if(substr($i, 0, 8) == "Disable_") {
					$disallowWhat = substr($i, 8);
					$disallowWhat = str_replace("_", "-", $disallowWhat);
					$disallowWhat = trim(strtolower($disallowWhat));

					$newDisallow[] = $disallowWhat;
				}
			}

			$groups[$groupId]['Name'] = $name;
			$groups[$groupId]['Disallow'] = $newDisallow;

			file_put_contents("../data/groups.json", json_encode($groups));

			header("Location: index.php?page=groups");
			exit;
		}
		else {
			$error222 = "Only alphanumeric names are allowed.";
		}
	}
?>

<div style="padding-left: 20px;">

	<h3>Edit Group: <?=$groupRow['Name'];?></h3>
	<hr />

	<?php if(isset($error222) && $error222 != '') { ?>

	<div class="alert alert-danger"><strong>Error:</strong> <?=$error222;?></div>

	<?php } ?>

	<form action="" method="POST">
		<table width="100%" class="table table-striped table-hover">
			<thead>
				<tr>
					<th>Alphanumeric Name</th>
					<th>Disabled Tools</th>
					<th width="120px">Actions</th>
				</tr>
			</thead>
			<tbody>
				<tr>
					<td width="200px">
						<input type="text" class="form-control" name="name" value="<?=$groupRow['Name'];?>">
					</td>
					<td>
						<div style="padding: 0 15px;">
						<div class="row">
						<?php
							require_once "../libraries/tools.php";

							$tools = new ToolManager();
							$tools->setDatabase($db);
							$tools->init();

							$toolData = $tools->getToolData();

							$allTools = array();
							foreach($toolData as $cat=>$toolsinside) {
								foreach($toolsinside as $singleTool) {
									$allTools[] = $singleTool;
								}
							}

							$i = 0;
							$toolsSoFar = 0;

							$toolsColumn = array(
								ceil(count($allTools) / 4) + 1,
								ceil(count($allTools) / 4),
								ceil(count($allTools) / 4),
								ceil(count($allTools) / 4)
							);

							$col = 0;

							foreach($allTools as $tool) {
								$i++;
								$specialName = $tool[0];
								$specialName = str_replace(" ", "_", $specialName);
								$specialName = str_replace(".", "-", $specialName);

								if($col == 0) {
									echo "<div class='col-md-3'>";
									$col = 1;
								}
								$toolsSoFar++;

								if($toolsSoFar == $toolsColumn[$col-1]) {
									$col++;
									$toolsSoFar = 0;
									echo "</div><div class='col-md-3'>";
								}

								$checked = false;

								foreach($groupRow['Disallow'] as $no) {
									$toolNameNo = str_replace(" ", "-", $tool[0]);
									$toolNameNo = str_replace(".", "-", $toolNameNo);
									$toolNameNo = strtolower(trim($toolNameNo));
									if($no == $toolNameNo) $checked = true;
								}
						?>
						<label style="font-weight: normal;" for="CheckBox<?=$i;?>"><input type="checkbox" id="CheckBox<?=$i;?>" name="Disable_<?=$specialName;?>"<?php if($checked) { ?> checked<?php } ?>>&nbsp;<?=$tool[0];?></label>
						<?php
							}
						?>
						</div>
						</div>
						</div>
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