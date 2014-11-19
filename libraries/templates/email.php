		<div class="pageHeader">
			<div class="container">
				<h2><?=$lang->translate($title, "tool-name");?></h2>
			</div>
		</div>

		<div class="container">
			<div class="row">
				<div class="col-lg-8 col-md-8 col-sm-8">
					<?php if($validation_error) { ?>
					<div class="alert alert-danger" style="margin-bottom: 40px;">
						<strong><?=$lang->translate("Oops!", "error");?></strong> <?=$lang->translate("The information entered did not validate. Please try again.", "error");?>
					</div>
					<?php } ?>

					<h3><?=$lang->translate("Enter an email address to continue", "header");?></h3>
					<hr />

					<form action="" method="POST" style="padding-top: 10px;">
						<input type="hidden" name="doFormPostback" value="<?=md5($title);?>">
						
						<div class="input-group">
							<input type="text" name="<?=$parameters[0];?>" class="form-control input-lg input-block placeholder" value="john.doe@example.com">
						
							<span class="input-group-btn">
								<button type="submit" class="btn btn-success btn-lg"><?=$lang->translate("Continue", "button");?></button>
							</span>
						</div>
					</form>
				</div>
				<div class="col-lg-4 col-md-4 col-sm-4">
					<div style="padding: 15px;">
						<?php
							echo $ads->getBanner("300x250");
						?>
					</div>
				</div>
			</div>

		<?php
			if($_GET['embed'] !== '5') {
		?>
			<div style="padding-top: 20px;">
				<h3 style="margin-top: 0px; margin-bottom: 25px;"><?=$lang->translate("Here's some more tools for you...", "header");?></h3>

				<div class="toolPaneListContainer" style="max-height: 165px; overflow: hidden;">

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
							if(!in_array($randTool, $randomTools) && $randTool[0] != $title) {
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
		<?php } ?>
		</div>