<?php
	$title = "Home";
	$path = "";

	require "{$path}structures/header.php";
	require_once "{$path}libraries/tools.php";

	$tools = new ToolManager();
	$tools->setDatabase($db);
	$tools->init();

	$toolData = $tools->getToolData();
?>
		<div class="frontPageBanner">
			<div class="container">
				<div class="row">
					<div class="col-lg-8 col-md-8 col-sm-8">
						<h1><?=$lang->translate($db->read("Home Page Head"), "home");?></h1>
						<p><?=$lang->translate($db->read("Home Page Body"), "home");?></p>
						
						<a href="<?=$path;?>tools.php" class="btn btn-danger btn-lg"><?=$lang->translate("Get Started", "home");?></a>
					</div>
					<div class="col-lg-4 col-md-4 col-sm-4">
						<img src="<?=$db->read("Home Banner 360x200");?>" alt="Search Engine Optimization" class="img-responsive visible-lg visible-md">
					</div>
				</div>
			</div>
		</div>

		<div class="callToAction">
			<div class="container">
				<div class="row">
					<div class="col-lg-8 col-sm-8 col-md-8">
						<h3><?=$lang->translate($db->read("Home Page Sub"), "head");?></h3>
					</div>
					<div class="col-lg-4 col-sm-4 col-md-4 visible-lg visible-md">
						<div style="text-align: right;">
							<a href="<?=$path;?>tools.php" class="btn btn-danger btn-info"><?=$lang->translate("Browse Tools", "home");?></a>
						</div>
					</div>
				</div>
			</div>
		</div>

		<div class="container">
			<div class="row">
				<?php
					$col = 8;
					if($db->read("Allow Registration") !== "N/A") { 
						if($db->read("Home Login Replacement") == "2") {
							$col = 12;
						}
					}
				?>
				<div class="col-lg-<?=$col;?> col-md-<?=$col;?> col-sm-<?=$col;?>">
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

						$max = 10;
						if($db->read("Allow Registration") !== "N/A") { 
							if($db->read("Home Login Replacement") == "2") $max = 14;
						}
						while(count($randomTools) < $max) {
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
				<?php
					$showbox = true;
					if($db->read("Allow Registration") === "Off") { 
						if($db->read("Home Login Replacement") == "2") {
							$showbox = false;
						}
					}

					if($showbox) {
				?>
				<div class="col-lg-4 col-md-4 col-sm-4">
					<?php
						$show = false;

						if($db->read("Allow Registration") !== "N/A") { 
							if($db->read("Home Login Replacement") === "1" || $db->read("Home Login Replacement") === "") {
								$show = true;
							}
							else {
								if($db->read("Home Login Replacement") == "4") {
									echo str_replace("<h3>", "<h3 style='margin-top: 0px;'>", file_get_contents("data/homebox.html"));
								}
							}
						}
						else {
							$show = true;
						}

						if($show) {
					?>
					<h3 style="margin-top: 10px; margin-bottom: 25px;"><?=$lang->translate("Account Login", "header");?></h3>
					
					<?php if(!$account->isLoggedIn()) { ?>
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
					<?php } else { ?>

						<?=str_replace("%1", "<a href='account.home.php'>".$userRow['Username']."</a>", $lang->translate("Welcome back, %1!", "variable"));?>

					<?php } } ?>
				</div>
				<?php 
					}
				?>
			</div>
		</div>
<?php
	require "{$path}structures/footer.php";
?>