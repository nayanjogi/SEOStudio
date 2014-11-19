<?php
	$privatekey = $db->read("Captcha Private Key");
	$publickey = $db->read("Captcha Public Key");
	
	$numberToolsBeforeCaptcha = str_replace("X", "0", $db->read("Captcha Tools Before Activation")); // maximum # tools used before captcha kicks in. Set to 0 to require captcha BEFORE using tools.
	$numberToolsRequireNewCaptcha = str_replace("X", "0", $db->read("Captcha Tools After Activation")); // maximum # tools used [after original captcha] before captcha kicks in again, to prevent overuse. Set to 0 to disable.
	
	/////////////////////

	$captchaFile = "{$path}data/captcha.json";
	$captchas = json_decode(file_get_contents($captchaFile), true);

	$myIP = $_SERVER['REMOTE_ADDR'];

	/////////////////////

	// Below: If a tool is being accessed, and data was posted (submitted), record it as a tool use.
	
	if(isset($_GET['id']) && isset($_POST['doFormPostback'])) {
		if(isset($captchas[$myIP])) {
			$currentToolsUsed = $captchas[$myIP];
			$currentToolsUsed++;
			$captchas[$myIP] = $currentToolsUsed;
			
			file_put_contents($captchaFile, json_encode($captchas));
		}
		else {
			$captchas[$myIP] = 1;
			
			file_put_contents($captchaFile, json_encode($captchas));
		}
	}
	
	/////////////////////

	// Below: If a captcha key is posted, check it. If it was right, set tool usage to 0 and continue.
	
	if(!isset($_GET['captcha'])) $_GET['captcha'] = "";
	
	if($_GET['captcha'] === '1') {
		$resp = recaptcha_check_answer(
			$privatekey,
			$_SERVER["REMOTE_ADDR"],
			$_POST["recaptcha_challenge_field"],
			$_POST["recaptcha_response_field"]
		);
		if(!$resp->is_valid) {
			header("Location: {$path}libraries/blocked.php?fail=1&e=".$resp->error);
			exit;
		}
		else {
			
			$captchas[$myIP] = 0;
			
			file_put_contents($captchaFile, json_encode($captchas));

			header("Location: ../tools.php");
			exit;
		}
	}

	///////////////////

	// Below: If the IP is new, and captcha is required before tool use, send them to the captcha page.
	// 	      Otherwise, if they aren't new, but have exceeded their max tools per captcha, resend them to the captcha.

	if(!isset($captchas[$myIP])) {
		if(isset($_GET['id']) && $numberToolsBeforeCaptcha == 0) {
			if($db->read('Captcha') !== 'Off') {
				header("Location: {$path}libraries/blocked.php");
				exit;
			}
		}
	}
	else {
		$currentToolsUsed = 0;
		if(isset($captchas[$myIP])) $currentToolsUsed = $captchas[$myIP];
		
		if($currentToolsUsed >= $numberToolsRequireNewCaptcha && $numberToolsRequireNewCaptcha !== 0) {
			if(strpos($_SERVER['PHP_SELF'], "/libraries/blocked.php") === false) {
				if($db->read('Captcha') !== 'Off') {
					header("Location: {$path}libraries/blocked.php");
					exit("You're required to enter a Captcha Code before continuing. This website does not tolerate robots. You'll also be required to enter captcha if you continue using tools. Please <a href='{$path}lib/captcha-please.php'>click here</a> to enter the captcha now.");
				}
			}
		}
	}
	
?>
