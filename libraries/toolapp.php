<?php
	
	define('SINGLE_DOMAIN_NAME', 'domain-name-single.php');
	define('THREE_DOMAIN_NAMES', 'domain-name-three.php');
	define('SINGLE_TEXTAREA', 'textarea-single.php');
	define('TAGS_TEXT', 'tags-text.php');
	define('EMAIL', 'email.php');
	define('NONE', 'none');

	define('VALID_URL', 'validate-url');
	define('VALID_TAGS', 'validate-tags');

	class ApplicationManager {
		public $pageName;
		public $requesting;
		public $processorName;
		public $inputs;
		public $inputValidation;
		public $validateOn = array();
		public $required = array();
		private $storage = array();
		private $memory = array();
		public $forcingProceed;
		public $isOverriding = false;
		public $overrideTo;

		function setPageName($newName) {
			$this->pageName = $newName;
		}
		function setProcessor($fileName) {
			$this->processorName = $fileName;
		}
		function requestForm($id) {
			$this->requesting = $id;
		}
		function overrideForm($id) {
			$this->isOverriding = true;
			$this->overrideTo = $id;
		}
		function setInputNames($names) {
			$this->inputs = $names;
		}
		function setInputValidation($validation) {
			$this->inputValidation = $validation;
		}
		function setInput($name, $value) {
			$this->storage[$name] = $value;
		}
		function getInput($name) {
			if(isset($this->storage[$name])) {
				return $this->storage[$name];
			}
			return false;
		}
		function setInputValidates($inputs) {
			$this->validateOn = $inputs;
		}
		function setInputRequired($inputs) {
			$this->required = $inputs;
		}

		function setMemory($name, $value) {
			$this->memory[$name] = $value;
		}
		function getMemory($name) {
			if(isset($this->memory[$name]))
				return $this->memory[$name];
			else
				return false;
		}
		function forceProcessor() {
			$this->forcingProceed = true;
		}
	}

?>