<?php

	function isProperURL($url) {
		if (filter_var($url, FILTER_VALIDATE_URL) !== false) {
			return true;
		}

		return false;
	}

	function isValid($url) {
		global $path;
		global $account;

		if(!isProperURL($url)) return false;

		$ch = curl_init($url);
		curl_setopt($ch, CURLOPT_HEADER, false);
		curl_setopt($ch, CURLOPT_FAILONERROR, true);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array("User-Agent: Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.15) Gecko/20080623 Firefox/2.0.0.15") ); // request as if Firefox
		curl_setopt($ch, CURLOPT_NOBODY, true);
		curl_setopt($ch, CURLOPT_TIMEOUT, 10);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		$connect = curl_exec($ch);

		if(curl_errno($ch) === 0) {
			// connect success

			$clientDataFile = "{$path}data/client-data.json";

			if(file_exists($clientDataFile)) {
				if(isset($_GET['id'])) {
					$toolId = $_GET['id'];
					$detectedTool = ucwords(str_replace("-", " ", $toolId));

					$clientData = json_decode(file_get_contents($clientDataFile), true);
					$new = array(
						'Visitor' => $_SERVER['REMOTE_ADDR'],
						'Tool' => $detectedTool,
						'Data' => $url,
						'Time' => time()
					);

					if($account->isLoggedIn()) {
						$userRow = $account->getUserRow();
						$new['Visitor'] = $userRow['Username'];
					}

					$clientData[] = $new;
					
					$clientData = json_encode($clientData);
					file_put_contents("{$path}data/client-data.json", $clientData);
				}
			}

			curl_close($ch);
			return true;
		}

		curl_close($ch);
		return false;
	}
?>
