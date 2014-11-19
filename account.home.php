<?php
	$title = "Account Home";
	$path = "";

	require "{$path}structures/header.php";
	require_once "{$path}libraries/tools.php";

	$tools = new ToolManager();
	$tools->setDatabase($db);
	$tools->init();

	$toolData = $tools->getToolData();

	if(!$account->isLoggedIn()) {
		header("Location: account.login.php");
		exit;
	}

	$userRow = $account->getUserRow();

	if(isset($_POST['save'])) {
		if($db->read("Collect Emails") !== "Off") {
			$email = $_POST['email'];
		}
		else {
			$email = "";
		}
		$pass = $_POST['password'];

		if($db->read("Collect Emails") !== "Off") {
			if(strlen($email) > 5 && strpos($email, ".") !== false && strpos($email, "@") !== false) {
				if($email != $userRow['Email']) {
					$account->updateUserColumn($userRow['Username'], "Email", $email);
				}
			}
		}

		if(strlen($pass) > 2) {
			$account->updateUserColumn($userRow['Username'], "Password", crypt($pass));
		}

		$saved = true;
		header("Location: account.home.php?saved=1");
		exit;
		
	}

	if(!isset($userRow['Email'])) {
		$userRow['Email'] = "";
	}

?>
		<div class="pageHeader">
			<div class="container">
				<h2><?=$lang->translate("Your Account", "title");?></h2>
			</div>
		</div>

		<?php if(isset($_GET['saved'])) { ?>
		<div class="container">
			<div class="alert alert-success">
				<?=$lang->translate("Your settings have been saved.", "error");?>
			</div>
		</div>
		<?php } ?>

		<div class="container">

			<h3 style="margin-top: 10px; margin-bottom: 25px;"><?=str_replace("%1", $userRow['Username'], $lang->translate("Welcome back, %1!", "variable"));?></h3>

		</div>

		<div class="container" style="text-align: left;">
					
					<div class="toolPaneListContainer">

					<?php
						$allTools = array();
						foreach($toolData as $cat=>$toolsinside) {
							foreach($toolsinside as $singleTool) {
								$allTools[] = $singleTool;
							}
						}

						$randomTools = array();

						while(count($randomTools) < 21) {
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

		<div class="container">

			<h3 style="margin-top: 10px; margin-bottom: 25px;"><?=$lang->translate("Change Your Settings", "variable");?></h3>

		</div>

		<div class="container" style="text-align: left;">
			<div class="loginBox">
				<form role="form" action="account.home.php" method="POST">
					<input type="hidden" name="save" value="1">

					<?php if($db->read("Collect Emails") !== "Off") { ?><div class="form-group" style="margin-top: 20px;">
						<label for="input3"><?=$lang->translate("Email", "label");?></label>
						<input type="text" name="email" class="form-control" id="input3" value="<?=$userRow['Email'];?>">
					</div><?php } ?>
					<div class="form-group" style="margin-top: 20px;">
						<label for="input2"><?=$lang->translate("Password", "label");?></label>
						<input type="password" name="password" class="form-control" id="input2">
					</div>
					<p style="margin-top: 25px; margin-bottom: 0px; text-align: right;">
						<?php if($db->read("Allow Registration") != "Off") { ?><input type="submit" class="btn btn-info" style="margin-bottom: 5px;" value="<?=$lang->translate("Continue", "button");?>"/><?php } ?>
					</p>
				</form>
			</div>
		</div>
<?php
	require "{$path}structures/footer.php";
?>