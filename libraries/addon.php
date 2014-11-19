<?php
	
	/*
		Addon class
		Adds native support for addons to successfully integrate with the application
	*/

	class Addon {

		private $path;

		function setPath($pathDir) {
			if(file_exists($pathDir . "libraries/database.php")) {
				$this->path = $pathDir;
				return true;
			}
			else {
				return false;
			}
		}

		function createDatabaseHandle() {
			require_once $this->path . "libraries/database.php";

			$db = new DatabaseManager($this->path . "data/main.db");

			if(!$db->open()) 
				return false;
			if(!$db->canRead()) 
				return false;

			return $db;
		}

		function newBlankFile($pathName) {
			return file_put_contents($this->path . $pathName, "");
		}

		function newClonedFile($pathName, $source) {
			return file_put_contents($this->path . $pathName, $source);
		}

		function http($url) {
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $url);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
			curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 15);
			curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.13) Gecko/20080311 Firefox/2.0.0.13');
			$data = curl_exec($ch);
			curl_close($ch);

			return $data;
		}

		function checkAddonExists($addonName) {
			$addonFile = $this->path . "data/addons.json";

			if(file_exists($addonFile)) {
				$addons = file_get_contents($addonFile);
				$addons = json_decode($addons, true);

				return isset($addons[$addonName]);
			}
			else {
				 return !(file_put_contents($addonFile, "[]"));
			}
		}

		function updateAddonRow($addonName, $new) {
			$addonFile = $this->path . "data/addons.json";

			if(file_exists($addonFile)) {
				$addons = file_get_contents($addonFile);
				$addons = json_decode($addons, true);

				$current = $addons[$addonName];

				foreach($new as $i=>$v) {
					$addons[$addonName][$i] = $v;
				}

				return file_put_contents($addonFile, json_encode($addons));
			}
			else {
				 return !(file_put_contents($addonFile, "[]"));
			}
		}

		function createAddonRow($addonName, $new) {
			$addonFile = $this->path . "data/addons.json";

			if(file_exists($addonFile)) {
				$addons = file_get_contents($addonFile);
				$addons = json_decode($addons, true);

				$addons[$addonName] = $new;

				return file_put_contents($addonFile, json_encode($addons));
			}
			else {
				 return !(file_put_contents($addonFile, "[]"));
			}
		}
	}

?>