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

	$db->write("Default Group", $groupRow['Name']);

	header("Location: index.php?page=groups");
	exit;
?>