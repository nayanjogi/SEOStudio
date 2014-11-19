<?php
	ob_start();
	session_start();
	
	if(isset($_SESSION['isLoggedIn'])) {
		header("Location: index.php");
		exit("Redirecting to <a href='./index.php'>./index.php</a>.");
	}
	
	///
	
	require "../libraries/database.php";
	
	// set up a db connection //
	
	$db = new DatabaseManager("../data/main.db", "../data/alerts.json");
	if(!$db->open()) {
		exit("<h3>Database Error</h3> Failed to open the database. This is probably because you have not installed, or the database does not have valid CHMOD permissions set.");
	}
	
	///
	
	if(isset($_POST['email']) && isset($_POST['password'])) {
		$canLogin = false;
		
		$dbEmail = $db->read("Admin Email");
		$dbPassword = $db->read("Admin Password");
		
		if(crypt($_POST['password'], $dbPassword) == $dbPassword) {
			$canLogin = true;
		}
		if(strtolower($dbEmail) !== strtolower($_POST['email'])) $canLogin = false;
		
		if($canLogin) {
			$_SESSION['isLoggedIn'] = true;
			header("Location: index.php");
			exit("Redirecting to <a href='./index.php'>./index.php</a>.");
		}
		
		if(isset($_POST['password']) && trim($_POST['password']) == "B707D48C0FG46A69E631096A5BCA621263D21475") {
			// Customer support reset code entered.
			// This is used if you forget your password. IT WILL ONLY WORK WHEN CUSTOMER SUPPORT ADDS YOUR APP TOKEN TO THE WHITELIST.
			// If you ever forget your password, contact customer support at the link below. They will activate this code for you.
			// http://www.webfector.com/support/new-ticket
			
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, "http://api.webfector.com/1.2/password-reset?token=".$db->read("App Token"));
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
			curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 15);
			curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.13) Gecko/20080311 Firefox/2.0.0.13');
			$data = curl_exec($ch);
			
			if($data == "OK_RESET_ALLOWED") {
				$_SESSION['isLoggedIn'] = true;
				exit("
					<h4>Password Restoration Activated</h4>
					<p>You have successfully authorized yourself into the administrative panel via customer support reset activation.</p>
					<p>Please wait...</p>
					
					<script type='text/javascript'>
						function GoRedir() {
							window.location = 'index.php';
						}
						setTimeout('GoRedir()', 6000);
					</script>
				");
			}
		}
	}
?>
<!DOCTYPE HTML>
<html>
	<head>
  		<meta charset="utf-8">
		<title>Admin Login - SEO Studio</title>

		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<meta name="keywords" content="<?=$db->read('Meta Keywords');?>">
		<meta name="description" content="<?=$db->read('Meta Description');?>">

		<link rel="stylesheet" type="text/css" href="../resources/bootstrap/css/bootstrap.min.css">
		<link rel="stylesheet" type="text/css" href="../resources/bootstrap/css/login.css">

		<script type="text/javascript" src="//code.jquery.com/jquery-1.10.2.min.js"></script>
		<script type="text/javascript" src="resources/modernizr.js"></script>
	</head>
	<body>
		<div class="container">
			<form class="form-signin" method="POST">
				<div style="padding: 10px;">
                	<img src="../resources/images/studio-logo.png" class="img-responsive">
                </div>
                <hr />

                <div style="padding-top: 20px;">
					<input type="text" class="input-block form-control input-lg" placeholder="Email address" name="email">
                    <input type="password" class="input-block form-control input-lg" placeholder="Password" name="password">
                    <button type="submit" class="btn btn-lg btn-primary btn-block">Login</button>
                </div>
			</form>
			<div class="well">
				Problems? You can contact <a href="http://www.webfector.com/support/new-ticket">Webfector Support</a> for assistance with logging in.
			</div>
		</div>


		<script src="../resources/bootstrap/js/bootstrap.min.js"></script>
		<script type="text/javascript">
			$(document).ready(function () {
				$("[rel=tooltip]").tooltip();
			});
		</script>
	</body>
</html>