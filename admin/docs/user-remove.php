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

	if($userId === 0) {
		exit("You can't remove this user!");;
	}

	unset($users[$userId]);

	file_put_contents("../data/users.json", json_encode($users));

	header("Location: index.php?page=users&cannot-remove-this-user=1");
	exit("Done");
?>
