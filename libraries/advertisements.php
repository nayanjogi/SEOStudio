<?php
	class AdBanner {
		private $code;
		
		function __construct() {
			$this->code = array();
		}
		
		function setHTML($size, $html) {
			$this->code[$size] = $html;
		}
		
		function getBanner($size) {
			if($this->code[$size]) {
				return $this->code[$size];
			}
			else {
				return "";
			}
		}
	}
?>