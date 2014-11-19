<?php
	if(!isset($sessionAlreadyStarted)) {
		ob_start();
		session_start();
	}
	
	if(!isset($path)) $path = "";
	
	require $path . "structures/app.php";

	if(!isset($_GET['embed'])) {
		$_GET['embed'] = '0';
	}
?>
<!DOCTYPE HTML>
<html>
	<head>
  		<meta charset="utf-8">
		<title><?=$lang->translate("$title", "title");?></title>

		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<meta name="keywords" content="<?=$db->read('Meta Keywords');?>">
		<meta name="description" content="<?=$db->read('Meta Description');?>">

		<link rel="stylesheet" type="text/css" href="<?=$path;?>resources/bootstrap/css/bootstrap.min.css">
		<link rel="stylesheet" type="text/css" href="<?=$path;?>resources/bootstrap/css/interface.css">

		<script type="text/javascript" src="//code.jquery.com/jquery-1.10.2.min.js"></script>
		<script type="text/javascript" src="<?=$path;?>resources/modernizr.js"></script>
	</head>
	<body>
		<?php
			if($_GET['embed'] !== '5') {
		?>
		<nav class="navbar navbar-inverse navbar-static-top" role="navigation">
			<div class="container">
				<div class="navbar-header">
					<button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#navbar1">
						<span class="sr-only">MENU</span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
					</button>

					<a class="navbar-brand" href="./<?=$path;?>">
					<?php
						if($db->read("Brand Method") == "Image") {
					?>
						<img src="<?=$path;?>img/logo.<?=$db->read("Brand Image");?>">
					<?php
						}
						else {
							echo $db->read("Brand Name");
						}
					?>
					</a>
				</div>
				<div class="collapse navbar-collapse" id="navbar1">
					<ul class="nav navbar-nav navbar-right">
						<li><a href="./<?=$path;?>"><?=$lang->translate("Home", "@Menu");?></a></li>
						<li><a href="./<?=$path;?>tools.php"><?=$lang->translate("Tools", "@Menu");?></a></li>
						<?php if($db->read("Admin Button") !== "Off") { ?><li><a href="./<?=$path;?>admin/"><?=$lang->translate("Admin", "@Menu");?></a></li><?php } ?>
						
						<?php if(!$account->isLoggedIn()) { ?><li class="dropdown">
							<a href="javascript:;" class="dropdown-toggle" data-toggle="dropdown"><?=$lang->translate("Account", "@Menu");?> <b class="caret"></b></a>

							<ul class="dropdown-menu">
								<li><a href="account.login.php"><?=$lang->translate("Login", "button");?></a></li>
								<li><a href="account.signup.php"><?=$lang->translate("Sign Up", "button");?></a></li>
							</ul>
						</li><?php } else { $userRow = $account->getUserRow(); ?><li class="dropdown">
							<a href="javascript:;" class="dropdown-toggle" data-toggle="dropdown"><?=ucfirst($userRow['Username']);?> <b class="caret"></b></a>

							<ul class="dropdown-menu">
								<li><a href="account.home.php"><?=$lang->translate("Dashboard", "button");?></a></li>
								<li><a href="account.logout.php"><?=$lang->translate("Sign out", "button");?></a></li>
							</ul>
						</li><?php } ?>

						<?php if(count($db->read("Languages")) > 1) { ?><li class="dropdown">
							<a href="javascript:;" class="dropdown-toggle" data-toggle="dropdown"><?=$lang->translate("Language", "@Menu");?> <b class="caret"></b></a>

							<ul class="dropdown-menu">
								<?php foreach($db->read("Languages") as $langg) { ?>
								<li><a href="?language=<?=$langg;?>"><?=$lang->translate($langg, "@Languages");?></a></li>
								<?php } ?>
							</ul>
						</li><?php } ?>
					</ul>
				</div>
			</div>
		</nav>
		<?php
			}
			else {
		?>

		<style type='text/css'>
			.pageHeader, .footer {
				display: none;
			}
			body {
				background: transparent;
			}
			.resultsPage .btn.btn-lg.btn-success {
				display: none;
			}
		</style>

		<?php
			}
		?>