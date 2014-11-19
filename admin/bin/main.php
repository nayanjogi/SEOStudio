<?php
	ob_start();
	session_start();

	$wordpress_mode = false;

	date_default_timezone_set("America/Phoenix");

	// Require login to access admin panel.

	if(!isset($_SESSION['isLoggedIn'])) {
		header("Location: login.php");
		exit("Redirecting to <a href='login.php'>login.php</a>.");
	}

	if(!isset($epath)) {
		$epath = "";
	}

	require "{$epath}../libraries/database.php";

	// New database connection.

	$db = new DatabaseManager("{$epath}../data/main.db", "{$epath}../data/alerts.json");
	if(!$db->open()) {
		exit("<h3>Database Error</h3> Failed to open the database. This is probably because you have not installed, or the database does not have valid CHMOD permissions set.");
	}
	
	if(isset($_GET['closeRate'])) {
		$db->write("Rate Popup", "Closed");
		$db->save();
	}

	if($db->read("Last Admin Login") !== "") {
		if($db->read("Last Admin Login") < (time() - 1800)) {
			$_SESSION['Outdated'] = $db->read("Last Admin Login");
		}
	}
	$db->write("Last Admin Login", time());
?>