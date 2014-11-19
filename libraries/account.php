<?php
	
	class Account {

		private $session;
		private $sessionName;
		private $sessionPassword;
		private $usersFile;
		private $groupsFile;

		public function __construct($users, $groups) {

			$this->usersFile = $users;
			$this->groupsFile = $groups;

			if(isset($_SESSION['username'])) {
				$this->sessionName = $_SESSION['username'];
				$this->sessionPassword = $_SESSION['password'];

				if($this->canLogIn()) {
					$this->session = true;

					$me = $this->getUserRow();
					if($_SERVER['REMOTE_ADDR'] != $me['Address']) {
						$this->updateRemoteAddress();
					}
					$this->updateLastOnline();
				}
			}

			return true;

		}

		private function canLogIn() {

			$users = json_decode(file_get_contents($this->usersFile), true);
			foreach($users as $user) {
				if($user['Username'] == $this->sessionName) {
					if($user['Password'] == $this->sessionPassword) {
						return true;
					}
				}
			}

			return false;

		}

		public function getUserRow($who = "me", $users = null) {

			if($users == null) {
				$users = json_decode(file_get_contents($this->usersFile), true);
			}

			if($who == "me") {
				foreach($users as $user) {
					if($user['Username'] == $this->sessionName) {
						return $user;
					}
				}
			}
			else {
				foreach($users as $user) {
					if(strtolower($user['Username']) == strtolower($who)) {
						return $user;
					}
				}
			}

			return false;

		}

		public function getGroupRow($myGroup) {

			$groups = json_decode(file_get_contents($this->groupsFile), true);
			foreach($groups as $group) {
				if(strtolower($group['Name']) == strtolower($myGroup)) {
					return $group;
				}
			}

			return null;

		}

		public function isLoggedIn() {
			return $this->session;
		}

		public function toolEnabled($toolName) {

			$myGroupRow = null;

			if($this->isLoggedIn()) {
				$me = $this->getUserRow();
				$myGroup = $me['Group'];
				$myGroupRow = $this->getGroupRow($myGroup);
			}
			else {
				$myGroupRow = $this->getGroupRow("public");
			}

			if($myGroupRow == null) {
				exit("
					<h3>Application Error</h3> 
					The user system has gone corrupt. User had group '$myGroup' 
					but it did not exist in the database."
				);
			}
			else {
				$disallow = $myGroupRow['Disallow'];

				$toolName = trim(strtolower($toolName));
				$toolName = str_replace(" ", "-", $toolName);

				foreach($disallow as $noGoTool) {
					$noGoTool = trim(strtolower($noGoTool));
					$noGoTool = str_replace(" ", "-", $noGoTool);

					if($noGoTool == $toolName) {
						return false;
					}
				}

				return true;
			}

			return false;
		}

		public function login($username, $password) {
			$users = json_decode(file_get_contents($this->usersFile), true);
			foreach($users as $user) {
				if(strtolower($user['Username']) == strtolower($username)) {
					if(crypt($password, $user['Password']) == $user['Password']) {
						$_SESSION['username'] = $user['Username'];
						$_SESSION['password'] = $user['Password'];

						return true;
					}
				}
			}

			return false;
		}

		private function updateRemoteAddress() {

			$users = json_decode(file_get_contents($this->usersFile), true);

			foreach($users as $i=>$user) {
				if($user['Username'] == $this->sessionName) {
					$users[$i]['Address'] = $_SERVER['REMOTE_ADDR'];
					file_put_contents($this->usersFile, json_encode($users));
				}
			}

			return true;

		}

		private function updateLastOnline() {

			$users = json_decode(file_get_contents($this->usersFile), true);

			foreach($users as $i=>$user) {
				if($user['Username'] == $this->sessionName) {
					$users[$i]['LastVisit'] = time();
					file_put_contents($this->usersFile, json_encode($users));
				}
			}

			return true;

		}

		public function updateUserColumn($username, $columnName, $value) {

			$users = json_decode(file_get_contents($this->usersFile), true);

			foreach($users as $i=>$user) {
				if($user['Username'] == $username) {
					$users[$i][$columnName] = $value;
					file_put_contents($this->usersFile, json_encode($users));
				}
			}

			return true;

		}

	}

?>