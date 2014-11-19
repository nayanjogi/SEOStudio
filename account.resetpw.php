<?php
	$title = "Reset Password";
	$path = "";

	require "{$path}structures/header.php";
	require_once "{$path}libraries/tools.php";

	$tools = new ToolManager();
	$tools->setDatabase($db);
	$tools->init();

	$toolData = $tools->getToolData();

	if($account->isLoggedIn()) {
		header("Location: account.home.php");
		exit;
	}

	if(isset($_POST['username']) && trim($_POST['username']) != "") {
		$username = $_POST['username'];
		
		$user = $account->getUserRow($username);
		if($user === false) {
			$invalid = true;
		}
		else {
			if(isset($user['Email']) && $user['Email'] != "") {
				$protocol = strpos(strtolower($_SERVER['SERVER_PROTOCOL']),'https') === false ? 'http' : 'https';
				$host     = $_SERVER['HTTP_HOST'];
				$script   = $_SERVER['SCRIPT_NAME'];

				$currentUrl = $protocol . '://' . $host . $script;
				$token = rand(1111111, 9999999) . rand(1111111, 9999999) . rand(1111111, 9999999);
				$resetUrl = $currentUrl . "?token=" . $token;

				$fromName = $db->read("EmailService Name");
				$fromAddress = $db->read("EmailService Address");

				$account->updateUserColumn($user['Username'], "PwResetToken", $token);

				$send = mail($user['Email'], "Password Reset", "To reset your password, visit the link below:

$resetUrl", "From: {$fromName} <{$fromAddress}>");

				$ok = true;
			}
			else {
				$invalid = true;
			}
		}
	}

	if(isset($_GET['token'])) {
		if(isset($_POST['pword'])) {
			$merow = null;

			$pword = $_POST['pword'];

			if(strlen(trim($pword)) < 3) exit;

			$users = file_get_contents("data/users.json");
			$users = json_decode($users, true);

			foreach($users as $userRoww) {
				if(isset($userRoww['PwResetToken'])) {
					if($userRoww['PwResetToken'] == $_GET['token']) {
						$merow = $userRoww;
					}
				}
			}

			if($merow === null) exit;

			$account->updateUserColumn($merow['Username'], "PwResetToken", '');
			$account->updateUserColumn($merow['Username'], "Password", crypt($pword));

			$db->alert("The user " . $merow['Username'] . " reset their password.", "#D4E5F3");

			header("Location: account.login.php");
			exit;
		}
?>

		<div class="pageHeader">
			<div class="container">
				<h2><?=$lang->translate("Reset Password", "title");?></h2>
			</div>
		</div>

		<div class="container">

			<?php if(isset($invalid) && $invalid == true) { ?>
			<div class="alert alert-danger">
				<?=$lang->translate("That account does not exist, or there is no email on file.", "error");?>
			</div>
			<?php } ?>
			<?php if(isset($ok) && $ok == true) { ?>
			<div class="alert alert-success">
				<?=$lang->translate("An email has been sent to you. Please click the link inside to continue.", "error");?>
			</div>
			<?php } ?>

						<div class="loginBox">
							<form role="form" action="account.resetpw.php?token=<?=$_GET['token'];?>" method="POST">
								<div class="form-group">
									<label for="input1"><?=$lang->translate("New Password", "label");?></label>
									<input type="password" name="pword" class="form-control" id="input1" placeholder="<?=$lang->translate("Enter password", "input");?>">
								</div>
								<p style="margin-top: 25px; margin-bottom: 0px; text-align: right;">
									
									<button type="submit" class="btn btn-primary" style="margin-bottom: 5px;"><?=$lang->translate("Continue", "button");?></button>
								</p>
							</form>
						</div>

		</div>
<?php
		require "{$path}structures/footer.php";
		exit;
	}
?>
		<div class="pageHeader">
			<div class="container">
				<h2><?=$lang->translate("Reset Password", "title");?></h2>
			</div>
		</div>

		<div class="container">

			<?php if(isset($invalid) && $invalid == true) { ?>
			<div class="alert alert-danger">
				<?=$lang->translate("That account does not exist, or there is no email on file.", "error");?>
			</div>
			<?php } ?>
			<?php if(isset($ok) && $ok == true) { ?>
			<div class="alert alert-success">
				<?=$lang->translate("An email has been sent to you. Please click the link inside to continue.", "error");?>
			</div>
			<?php } ?>

						<div class="loginBox">
							<form role="form" action="account.resetpw.php" method="POST">
								<div class="form-group">
									<label for="input1"><?=$lang->translate("Username", "label");?></label>
									<input type="text" name="username" class="form-control" id="input1" placeholder="<?=$lang->translate("Enter username", "input");?>">
								</div>
								<p style="margin-top: 25px; margin-bottom: 0px; text-align: right;">
									
									<button type="submit" class="btn btn-primary" style="margin-bottom: 5px;"><?=$lang->translate("Continue", "button");?></button>
								</p>
							</form>
						</div>

		</div>
<?php
	require "{$path}structures/footer.php";
?>