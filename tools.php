<?php
	$title = "Our Tools";
	$path = "";

	require "{$path}structures/header.php";
	require_once "{$path}libraries/tools.php";

	$tools = new ToolManager();
	$tools->setDatabase($db);
	$tools->init();
	//$tools->setupTools();
	//exit;

	$toolData = $tools->getToolData();
?>

		<div class="pageHeader">
			<div class="container">
				<h2><?=$lang->translate("Our Tools", "title");?></h2>
			</div>
		</div>

		<div class="container">
			<?php
				if(isset($_GET['error']) && $_GET['error'] == '404') {
			?>
			<div class="errorBox">
				<strong>An error occured:</strong> The tool requested was not configured properly and could not be displayed.
			</div>
			<?php
				}
			?>
			
			<?php
				if(isset($_GET['error']) && $_GET['error'] == '403') {
			?>
			<div class="errorBox">
				<?php 
					if(!$account->isLoggedIn()) {
						echo $lang->translate("You do not have access to this tool.", "error");
						echo " ";
						echo $lang->translate("Logging in may fix this problem.", "error");
					} else {
						echo $lang->translate("You do not have access to this tool.", "error");
						echo " ";
						echo $lang->translate("Sorry.");
					}
				?>
			</div>
			<?php
				}
			?>

			<div class="row">
				<div class="col-lg-10 col-md-10 col-sm-10">
					<?php 
						foreach($toolData as $category=>$cTools) {
					?>

					<h4><?=$lang->translate($category, "category");?></h4>
					<hr />

					<div class="toolPaneListContainer">

					<?php
							foreach($cTools as $thetool) {
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

					<?php
						}
					?>
				</div>
				<div class="col-lg-2 col-md-2 col-sm-2">
					<div class="visible-lg visible-md">
						<?php
							echo $ads->getBanner("160x600");
							echo PHP_EOL;
							echo $ads->getBanner("160x600");
						?>
					</div>
					<div class="visible-xs visible-sm">
						<?php
							echo $ads->getBanner("300x250");
						?>
					</div>
				</div>
			</div>
		</div>

<?php
	require "{$path}structures/footer.php";
?>