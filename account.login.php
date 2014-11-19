<?php
	$title = "Account Login";
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
		$password = $_POST['password'];

		if($account->login($username, $password)) {
			// thanks to the $account->login function, we're now logged in.
			// that was easy.

			$verified = true;

			if($db->read("Verify Emails") === "On") {
				$verified = false;

				$userRow = $account->getUserRow($username);
				if(isset($userRow['Verified'])) {
					if($userRow['Verified'] == true) {
						$verified = true;
					}
				}
				else {
					$verified = true;
				}
			}

			if($verified) {
				$db->alert("Visitor (".$_SERVER['REMOTE_ADDR'].") logged in as '$username'", "#e0ecff");
				header("Location: account.home.php");
				exit;
			}
			else {
				$_SESSION['username'] = '';
				$_SESSION['password'] = '';
				unset($_SESSION['username']);
				unset($_SESSION['password']);

				$invalid2 = true;
			}
		}
		else {
			$db->alert("Visitor (".$_SERVER['REMOTE_ADDR'].") failed to login as '$username'", "#ff837a");

			$invalid = true;
		}
	}
?>
		<div class="pageHeader">
			<div class="container">
				<h2><?=$lang->translate("Account Login", "title");?></h2>
			</div>
		</div>

		<div class="container">

			<?php if(isset($invalid) && $invalid == true) { ?>
			<div class="alert alert-danger">
				<?=$lang->translate("Your username or password is invalid.", "error");?>
			</div>
			<?php } ?>
			<?php if((isset($invalid2) && $invalid2 == true) || isset($_GET['verify'])) { ?>
			<div class="alert alert-danger">
				<?=$lang->translate("Please check your email for the verification link.", "error");?>
			</div>
			<?php } ?>

						<div class="loginBox">
							<form role="form" action="account.login.php" method="POST">
								<div class="form-group">
									<label for="input1"><?=$lang->translate("Username", "label");?></label>
									<input type="text" name="username" class="form-control" id="input1" placeholder="<?=$lang->translate("Enter username", "input");?>">
								</div>
								<div class="form-group" style="margin-top: 20px;">
									<label for="input2"><?=$lang->translate("Password", "label");?> (<a href="account.resetpw.php"><?=$lang->translate("forgot?", "label");?></a>)</label>
									<input type="password" name="password" class="form-control" id="input2" placeholder="<?=$lang->translate("Enter password", "input");?>">
								</div>
								<p style="margin-top: 25px; margin-bottom: 0px; text-align: right;">
									<?php if($db->read("Allow Registration") != "Off") { ?><a href="account.signup.php" class="btn btn-info" style="margin-bottom: 5px;"><?=$lang->translate("Make an account", "button");?></a><?php } ?>
									<button type="submit" class="btn btn-primary" style="margin-bottom: 5px;"><?=$lang->translate("Login", "button");?></button>
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