<?php
	$title = "Sign Up";
	$path = "";

	require "{$path}structures/header.php";
	require_once "{$path}libraries/tools.php";

	if($db->read("Allow Registration") == "Off") {
		header("Location: ./");
		exit;
	}

	$tools = new ToolManager();
	$tools->setDatabase($db);
	$tools->init();

	$toolData = $tools->getToolData();

	if($account->isLoggedIn()) {
		header("Location: account.home.php");
		exit;
	}

	if(isset($_GET['token'])) {
		$token = $_GET['token'];

		$merow = null;

		$users = file_get_contents("data/users.json");
		$users = json_decode($users, true);

		foreach($users as $userRoww) {
			if(isset($userRoww['VerifyToken'])) {
				if($userRoww['VerifyToken'] == $token) {
					$merow = $userRoww;
				}
			}
		}

		if($merow === null) exit;

		$account->updateUserColumn($merow['Username'], "VerifyToken", '');
		$account->updateUserColumn($merow['Username'], "Verified", true);

		$db->alert("The user " . $merow['Username'] . " verified their account.", "#D4E5F3");

		$_SESSION['username'] = $merow['Username'];
		$_SESSION['password'] = $merow['Password'];

		header("Location: account.home.php");
		exit;
	}

	if(isset($_POST['username'])) {
		$username = $_POST['username'];
		$password = $_POST['password'];
		if($db->read("Collect Emails") !== "Off") {
			$email = $_POST['email'];
		}
		else {
			$email = "";
		}
		$group = $db->read("Default Group");
		
		if(ctype_alnum($username)) {
			if(strlen(trim($password)) > 2) {

				$usernameTaken = false;

				$users = file_get_contents("data/users.json");
				$users = json_decode($users, true);
				foreach($users as $userRow2) {
					if(strtolower($userRow2['Username']) == strtolower($username)) {
						$usernameTaken = true;
					}
				}

				if(!$usernameTaken) {
					if($db->read("Collect Emails") === "Off" || (strlen($email) > 5 && strpos($email, ".") !== false && strpos($email, "@") !== false)) {
						if($db->read("Collect Emails") === "Off") {
							$arr = array(
								'Username' => $username,
								'Group' => $group,
								'Password' => crypt($password),
								'Address' => ''
							);
						}
						else {
							$arr = array(
								'Username' => $username,
								'Group' => $group,
								'Password' => crypt($password),
								'Address' => '',
								'Email' => $email
							);
						}

						if($db->read("Verify Emails") === "On") {
							$arr['Verified'] = false;

							$protocol = strpos(strtolower($_SERVER['SERVER_PROTOCOL']),'https') === false ? 'http' : 'https';
							$host     = $_SERVER['HTTP_HOST'];
							$script   = $_SERVER['SCRIPT_NAME'];

							$currentUrl = $protocol . '://' . $host . $script;
							$token = rand(1111111, 9999999) . rand(1111111, 9999999) . rand(1111111, 9999999);
							$resetUrl = $currentUrl . "?token=" . $token;

							$fromName = $db->read("EmailService Name");
							$fromAddress = $db->read("EmailService Address");

							$arr['VerifyToken'] = $token;

							$send = mail($email, "Verify Account", "To verify your account, visit the link below:

$resetUrl", "From: {$fromName} <{$fromAddress}>");
						}

						$users[] = $arr;

						file_put_contents("data/users.json", json_encode($users));

						$db->alert("The user <strong>".$username."</strong> (".$_SERVER['REMOTE_ADDR'].") joined the website.", "#e0ecff");
						
						if($db->read("Verify Emails") !== "On") {
							$account->login($_POST['username'], $_POST['password']);
							header("Location: account.home.php");
						}
						else {
							header("Location: account.login.php?verify");
						}

						exit;
					}
					else {
						$email = "Please enter a valid email.";
					}
				}
				else {
					$taken = "That username is not available.";
				}
			}
			else {
				$invalid = "Password is not good enough.";
			}
		}
		else {
			$alpha = "Only alphanumeric usernames are allowed.";
		}
	}
?>
		<div class="pageHeader">
			<div class="container">
				<h2><?=$lang->translate("Sign Up", "title");?></h2>
			</div>
		</div>

		<div class="container">

			<?php if(isset($email) && $email == true) { ?>
			<div class="alert alert-danger">
				<?=$lang->translate("Please enter a valid email address.", "error");?>
			</div>
			<?php } ?>
			<?php if(isset($invalid) && $invalid == true) { ?>
			<div class="alert alert-danger">
				<?=$lang->translate("Your username or password is invalid.", "error");?>
			</div>
			<?php } ?>
			<?php if(isset($taken) && $taken == true) { ?>
			<div class="alert alert-danger">
				<?=$lang->translate("That username is taken.", "error");?>
			</div>
			<?php } ?>
			<?php if(isset($alpha) && $alpha == true) { ?>
			<div class="alert alert-danger">
				<?=$lang->translate("Your username can only contain numbers or letters.", "error");?>
			</div>
			<?php } ?>

						<div class="loginBox">
							<form role="form" action="account.signup.php" method="POST">
								<div class="form-group">
									<label for="input1"><?=$lang->translate("Username", "label");?></label>
									<input type="text" name="username" class="form-control" id="input1" placeholder="<?=$lang->translate("Enter username", "input");?>">
								</div>
								<?php if($db->read("Collect Emails") !== "Off") { ?>
								<div class="form-group" style="margin-top: 20px;">
									<label for="input3"><?=$lang->translate("Email", "label");?></label>
									<input type="text" name="email" class="form-control" id="input3" placeholder="<?=$lang->translate("Enter email", "input");?>">
								</div><?php } ?>
								<div class="form-group" style="margin-top: 20px;">
									<label for="input2"><?=$lang->translate("Password", "label");?></label>
									<input type="password" name="password" class="form-control" id="input2" placeholder="<?=$lang->translate("Enter password", "input");?>">
								</div>
								<p style="margin-top: 25px; margin-bottom: 0px; text-align: right;">
									<?php if($db->read("Allow Registration") != "Off") { ?><input type="submit" class="btn btn-info" style="margin-bottom: 5px;" value="<?=$lang->translate("Continue", "button");?>"/><?php } ?>
								</p>
							</form>
						</div>

		</div>

		<div style="height: 50px;"></div>

		<div class="container" style="text-align: center;">
					<h3 style="margin-top: 10px; margin-bottom: 25px;"><?=$lang->translate("Here's just a few of our tools...", "home");?></h3>
					
					<div class="toolPaneListContainer">

					<?php
						$allTools = array();
						foreach($toolData as $cat=>$toolsinside) {
							foreach($toolsinside as $singleTool) {
								$allTools[] = $singleTool;
							}
						}

						$randomTools = array();

						while(count($randomTools) < 7) {
							$randTool = $allTools[rand(0, (count($allTools) - 1))];
							if(!in_array($randTool, $randomTools)) {
								$randomTools[] = $randTool;
							}
						}

							foreach($randomTools as $thetool) {
								$toolName = $thetool[0];
								$toolIcon = $thetool[1];
								$toolDescription = urldecode($thetool[2]);
								$toolId = strtolower(str_replace(array(".", " "), "-", $toolName));
					?>

						<div class="toolPaneHorizontal" rel="tooltip" title="<?=$lang->translate($toolDescription, "tool-description");?>">
							<a href="./<?=$path;?>tool.php?id=<?=$toolId;?>">
								<div class="toolPaneIcon">
									<img src="./<?=$path;?><?=$toolIcon;?>">
								</div>
								<div class="toolPaneInformation">
									<h4><?=$lang->translate($toolName, "tool-name");?></h4>
								</div>
							</a>
						</div>

					<?php
							}
					?>

					</div>

				</div>

		</div>
<?php
	require "{$path}structures/footer.php";
?>