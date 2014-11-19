<?php
	ob_start();
	session_start();
	$sessionAlreadyStarted = true;

	$path = "";
	require "{$path}libraries/toolapp.php";

	if(!isset($_GET['id'])) {
		header("Location: tools.php?error=403");
		exit;
	}

	if(isset($_GET['url'])) {
		$x = $_GET['url'];
		$x = str_replace(array("http://", "https://"), "", $x);
		$x = "http://" . $x;
		$_POST['url'] = $x;
		$_POST['doFormPostback'] = '2af90435ad26806cebd14a1b5ae3d7cf';
	}

	$toolId = strtolower(urldecode($_GET['id']));

	require_once "{$path}libraries/tools.php";
	
	if($toolId == "generate-seo-report") $toolId = "seo-report-generator";

	$application = new ApplicationManager();
	$toolPath = "{$path}tools/{$toolId}/";

	if(!file_exists($toolPath) || !file_exists($toolPath . "main.php")) {
		header("Location: tools.php?error=404");
		exit;
	}

	// does this person have access to this tool?

	require_once "libraries/account.php";

	$account = new Account("data/users.json", "data/groups.json");

	$canAccess = $account->toolEnabled($toolId);
	if(!$canAccess) {
		header("Location: tools.php?error=403");
		exit;
	}

	require $toolPath . "main.php";
	require_once "libraries/domain.php";

	// Let's grab the information the tool gave us. //

	$title = $application->pageName;
	$template = $application->requesting;
	$parameters = $application->inputs;
	$processor = $application->processorName;
	$validation = $application->inputValidation;
	$validation_error = false;
	$canProceed = false;

	$toolNamePath = strtolower(urldecode($_GET['id']));
	if($toolNamePath == "generate-seo-report") $toolNamePath = "seo-report-generator";
	
	// Process any submitted data. //

	if(isset($_POST['doFormPostback'])) {
		if($_POST['doFormPostback'] != md5('1')) {
			foreach($_POST as $i=>$v) {
				if($v == "http://www.example.com/") {
					$_POST[$i] = "";
				}
			}

			if($parameters !== 'auto') {
				foreach($parameters as $param) {
					if(isset($_POST[$param])) {
						$application->setInput($param, $_POST[$param]);
					}
				}
			}
			else {
				foreach($_POST as $param=>$val) {
					$application->setInput($param, $val);
				}
			}

			foreach($application->required as $validate) {
				if(!isset($_POST[$validate])) {
					$validation_error = true;
				}
			}

			if(!$validation_error) {
				foreach($application->validateOn as $validate) {
					if(isset($_POST[$validate]) && $_POST[$validate] != "") {
						if($validation == "validate-url") {
							if(!isValid($_POST[$validate])) {
								$validation_error = true;
							}
						}
						if($validation == "validate-tags") {
							if(strlen($_POST[$validate]) < 2) {
								$validation_error = true;
							} 
						}
					}
				}
			}

			if(!$validation_error) {
				$canProceed = true;
			}
		}
	}

	if($application->forcingProceed) {
		require "{$path}structures/header.php";

		$processorFilePath = "{$path}tools/$toolNamePath/" . $processor;
		if(file_exists($processorFilePath)) {
			echo "<div class='resultsPage'>";
			require $processorFilePath;
			echo "</div>";
			require "{$path}structures/footer.php";
			exit;
		}
		else {
			$toolerror = "This tool asked the framework to forcefully initialize the processor, but it had no processor to initialize.<br />
			Either the tool is misconfigured, or the processor is missing.";
			require "{$path}libraries/templates/tool-error.php";
			require "{$path}structures/footer.php";
			exit;
		}
	}

	// Done. Now let's draw the webpage based on the apps requests. //

	require "{$path}structures/header.php";

	if(!$application->isOverriding) {
		$templateFilePath = "{$path}libraries/templates/" . $template;
		if(!file_exists($templateFilePath)) {
			$toolerror = "This tool requested a template resource ($template) that did not exist.";
			require "{$path}libraries/templates/tool-error.php";
			require "{$path}structures/footer.php";
			exit;
		}
	}

	$tools = new ToolManager();
	$tools->setDatabase($db);
	$tools->init();

	$toolData = $tools->getToolData();

	if(!$canProceed) {
		if(!$application->isOverriding) {
			require $templateFilePath;
		}
	}
	else {
		$processorFilePath = "{$path}tools/$toolNamePath/" . $processor;
		if(file_exists($processorFilePath)) {
			echo "<div class='resultsPage'>";
			require $processorFilePath;
			echo "</div>";
		}
		else {
			$toolerror = "This tool does not have a processor page established. This means it cannot get results.";
			require "{$path}libraries/templates/tool-error.php";
			require "{$path}structures/footer.php";
			exit;
		}
	}

	if($application->isOverriding && !$canProceed) {
		require "{$path}tools/$toolNamePath/" . $application->overrideTo;
		require "{$path}structures/footer.php";
		exit;
	}
	else {
?>

<!--

	Requested tool:   <?=$title;?>
	Tool identifier:   <?=md5($title);?>
	Template:   <?=$template;?>
	Validation:   <?=$validation;?>

-->

<?php
		require "{$path}structures/footer.php";
	}
?>
