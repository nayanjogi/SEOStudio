<?php
	// Activate the DB Manager and Translator System //

	error_reporting(E_ALL); 
	ini_set( 'display_errors','1');
	
	$wordpress_mode = false;
	
	require_once "{$path}libraries/database.php";
	require_once "{$path}libraries/language.php";
	require_once "{$path}libraries/account.php";
	
	$db = new DatabaseManager("{$path}data/main.db", "{$path}data/alerts.json");

	if(!$db->open()) {
		header("Location: {$path}libraries/installation.php");
		exit;
	}
	if(!$db->canRead()) {
		header("Location: {$path}libraries/installation.php");
		exit;
	}

	if(!isset($_SESSION['selectedLanguage'])) {
		$_SESSION['selectedLanguage'] = $db->read("Default Language");
	}
	if(isset($_GET['language'])) {
		$_SESSION['selectedLanguage'] = $_GET['language'];
	}
	
	$lang = new Language("{$path}data/lang." . (strtolower($_SESSION['selectedLanguage'])) . ".json");
	if(!$lang->open($_SESSION['selectedLanguage'])) {
		exit("<h3>Language Database Error</h3> Failed to open the language database. This is due to either a malformed installation, or corrupted language files.");
	}

	// account

	$account = new Account("{$path}data/users.json", "{$path}data/groups.json");
	
	
	// requirements
	
	require_once $path . "libraries/advertisements.php";
	require_once $path . "libraries/recaptcha.php";
	require_once $path . "libraries/captcha.php";

	require_once $path . "libraries/domain.php";
	require_once $path . "libraries/simple_html_dom.php";
	require_once $path . "libraries/fault.php";
	
	$footerPathVar = $path;
	
	// Basic php.ini settings //
	
	ini_set("session.cookie_lifetime", 60*60*24*7); // last 1 week
	ini_set("session.gc_maxlifetime", 60*60*24*7); // last 1 week
	
	// home title
	
	if(!isset($title)) $title = "";

	if($title == "Home") {
		$title = $db->read("Home Page Title");
	}
	
	// ads
	
	$ads = new AdBanner();
	$ads->setHTML("160x600", 
		"<div style='text-align: center;'>" .
		$db->read("Adsense 160x600") .
		"</div>"
	);
	$ads->setHTML("728x90", 
		"<div style='text-align: center;'>" .
		$db->read("Adsense 728x90") .
		"</div>"
	);
	$ads->setHTML("300x250", 
		"<div style='text-align: center;'>" .
		$db->read("Adsense 300x250") .
		"</div>"
	);
	
	//
	
	$title = $lang->translate($title);
	
?>
