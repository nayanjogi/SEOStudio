<?php
	$name = $_GET['name'];
	$userRow = null;

	$groups = file_get_contents("../data/groups.json");
	$groups = json_decode($groups, true);

	$userId = 0;

	foreach($groups as $i=>$userRow2) {
		if(strtolower($userRow2['Name']) == strtolower($name)) {
			$userRow = $userRow2;
			$userId = $i;
		}
	}

	if($userRow == null) {
		header("Location: index.php?page=groups");
		exit;
	}

	if($userId == 1 || $userId == 2 || $userRow['Name'] == "public") {
		exit("You can't remove this group!");;
	}

	if($db->read("Default Group") == $userRow['Name']) {
		$db->write("Default Group", $groups[2]['Name']);
	}

	$users = file_get_contents("../data/users.json");	
	$users = json_decode($users, true);
	foreach($users as $i=>$userRow2) {
		if($userRow2['Group'] == $userRow['Name']) {
			$users[$i]['Group'] = $db->read("Default Group");
		}
	}

	file_put_contents("../data/users.json", json_encode($users));


	unset($groups[$userId]);

	file_put_contents("../data/groups.json", json_encode($groups));

	header("Location: index.php?page=groups&done=1");
	exit("Done");
?>
