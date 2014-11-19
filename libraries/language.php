<?php
	class Language {
		private $dbPath;
		private $dbJson; 
		private $dbIsOpen;
		private $lang;
		
		function __construct($pathToDb) {
			$this->dbPath = $pathToDb;
			
			return $this->dbIsOpen;
		}
		
		function open($languageName) {
			$languageName = strtolower($languageName);
			
			$this->dbIsOpen = false;
			if(!file_exists($this->dbPath)) return false;
			$this->dbJson = json_decode(file_get_contents($this->dbPath), true);
			if($this->dbJson === false) return false;
			$this->dbIsOpen = true;
			$this->lang = $languageName;
			return true;
		}
		
		function save() {
			ksort($this->dbJson);
			$jd = json_encode($this->dbJson);
			return file_put_contents($this->dbPath, $jd);
		}
		
		function translate($phrase, $category="") {
			if(isset($this->dbJson[trim($phrase)])) {
				if($this->dbJson[trim($phrase)][1] != $category) {
					$this->dbJson[trim($phrase)] = array($this->dbJson[trim($phrase)][0], $category);
					$this->save();
				}
				return $this->dbJson[trim($phrase)][0];
			}
			else {
				// make the phrase //
				
				if($phrase != "") {
					$this->dbJson[trim($phrase)] = array(trim($phrase), $category);
					$this->save();
				}
				
				return $phrase;
			}
		}
	}
?>
