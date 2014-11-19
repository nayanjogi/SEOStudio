<!DOCTYPE HTML>
<html>
	<head>
		<meta http-equiv='Content-Type' content='text/html; charset=utf-8'>
		<title>Welcome to SEO Studio</title>
		
		<link href="//fonts.googleapis.com/css?family=Open+Sans:400italic,600italic,400,600" rel="stylesheet">
		
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<meta name="keywords" content="">
		<meta name="description" content="">
		
		<link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.0.0/css/bootstrap.min.css">
		
		<script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
		<script src="//netdna.bootstrapcdn.com/bootstrap/3.0.0/js/bootstrap.min.js"></script>
	</head>
	<body style="padding: 60px 20px;">
		<div class="container">
			<div style="text-align: center;">
				<img src="http://www.webfector.com/wp-content/uploads/2014/03/Logo-Transparent-100px1.png" height="75px" alt="Webfector">
				<br /><br /><hr /><br />
				<img id="LoadingIndicator" src="../img/Windows8Loader.gif" alt="Loading">
				
				<br /><br /><br />
				
				<div id="NeedJS">JavaScript is required to install</div>
			</div>
		</div>
		
		<script>
			function BeginInstallation() {
				window.location = '../install/';
			}
			
			$(function() {
				
				console.log("Welcome to SEO Studio.");
				console.log("Script is not installed. Redirecting to installation in 5 seconds...");
				
				setTimeout('BeginInstallation()', 5000);
				
			});
			
			$("#NeedJS").hide();
		</script>
	</body>
</html>
