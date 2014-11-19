<?php
	class DatabaseManager {
		private $dbPath;
		private $alertsPath;
		private $dbJson;
		private $isOpen;
		
		function __construct($pathToDb,$pathToAlerts) {
			$this->dbPath = $pathToDb;
			$this->alertsPath = $pathToAlerts;
			$this->isOpen = false;
		}
		
		function open() {
			if(file_exists($this->dbPath)) {
				$json = json_decode(file_get_contents($this->dbPath), true);
				$this->dbJson = $json;
				
				if($json) {
					$this->isOpen = true;
				}
				
				return $this->isOpen;
			}
			else {
				$this->isOpen = false;
				return false;
			}
		}
		
		function canRead() {
			$json = json_decode(file_get_contents($this->dbPath), true);
			if($json === false || $json === null) return false;
			return true;
		}
		
		function write($variable, $value) {
			$variable = $this->format($variable);
			
			if(!is_array($value)) $this->dbJson[$variable] = urlencode($value);
			else $this->dbJson[$variable] = $value;
			
			ksort($this->dbJson);
			
			return file_put_contents($this->dbPath, json_encode($this->dbJson));
		}
		
		function save() {
			ksort($this->dbJson);
			return file_put_contents($this->dbPath, json_encode($this->dbJson));
		}
		
		function format($string) {
			$string = trim($string);
			return $string;
		}

		function read($variable, $writeIfNotExist = true) {
			$variable = $this->format($variable);

			if(isset($this->dbJson[$variable])) {
				if(is_array($this->dbJson[$variable])) {
					return $this->dbJson[$variable];
				}
				return urldecode($this->dbJson[$variable]);
			}
			else {
				if($writeIfNotExist) {
					$this->dbJson[$variable] = '';
					$this->save();
					
					return false;
				}
				else {
					return false;
				}
			}
		}

		function alert($text, $color="#eee", $time=null) {
			if($time == null) $time = time();

			$alerts = file_get_contents($this->alertsPath);
			$alerts = json_decode($alerts, true);

			array_unshift($alerts, array($color, $text, $time));

			file_put_contents($this->alertsPath, json_encode($alerts));
			return true;
		}
	}
?>
