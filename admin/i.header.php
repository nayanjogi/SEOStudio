<?php
	require "bin/main.php";
?>
<!DOCTYPE HTML>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<title>Admin - SEO Studio</title>
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<meta name="description" content="">
		<meta name="author" content="">
		
		<link href="css/bootstrap.css" rel="stylesheet">
		<link href="css/custom3.0.css" rel="stylesheet">
		<link href="css/altusia.css" rel="stylesheet">

		<script type="text/javascript" src="//code.jquery.com/jquery-1.10.2.min.js"></script>
	</head>
	<body>
		<table width="100%" class="MainFrame">
			<tr>
				<td width="225px">
					<div class="ctlAltusiaNavigation">
						<div style="padding-bottom: 10px;">
							<a href="index.php?page=home">
								<img src="../resources/images/studio-logo.png" width="100%" alt="SEO Studio" title="SEO Studio" border="0px">
							</a>
						</div>

						<div class="ctlAltusiaNavigationHeader">Configure</div>
						<a href="index.php?page=settings" class="ctlAltusiaNavigationItem">Settings</a>
						<a href="index.php?page=header-footer" class="ctlAltusiaNavigationItem">Header & Footer</a>
						<a href="index.php?page=registration" class="ctlAltusiaNavigationItem">User Registration</a>

						<div class="ctlAltusiaNavigationHeader">Customize</div>
						<a href="index.php?page=translations" class="ctlAltusiaNavigationItem">Translations</a>
						<a href="index.php?page=design-homepage" class="ctlAltusiaNavigationItem">Homepage Designer</a>
						<a href="index.php?page=meta" class="ctlAltusiaNavigationItem">Meta Tags</a>
						<a href="index.php?page=embed" class="ctlAltusiaNavigationItem">Embed Tools</a>

						<div class="ctlAltusiaNavigationHeader">Pages</div>
						<a href="index.php?page=terms" class="ctlAltusiaNavigationItem">Terms of Service</a>
						<a href="index.php?page=privacy" class="ctlAltusiaNavigationItem">Privacy Policy</a>

						<div class="ctlAltusiaNavigationHeader">Addons</div>
						<a href="index.php?page=shop-addons" class="ctlAltusiaNavigationItem">Shop Addons</a>
						<a href="index.php?page=manage-addons" class="ctlAltusiaNavigationItem">Manage Addons</a>

						<div class="ctlAltusiaNavigationHeader">Accounts</div>
						<a href="index.php?page=users" class="ctlAltusiaNavigationItem">Users</a>
						<a href="index.php?page=groups" class="ctlAltusiaNavigationItem">Groups</a>
						<a href="index.php?page=tool-usage" class="ctlAltusiaNavigationItem">Tool Usage</a>

						<div class="ctlAltusiaNavigationHeader">Services</div>
						<a href="index.php?page=recaptcha" class="ctlAltusiaNavigationItem">ReCAPTCHA</a>
						<a href="index.php?page=ads" class="ctlAltusiaNavigationItem">Ad Banners</a>
						<a href="index.php?page=analytics" class="ctlAltusiaNavigationItem">Google Analytics</a>
						<a href="index.php?page=engine" class="ctlAltusiaNavigationItem">Search Engine</a>

						<div class="ctlAltusiaNavigationHeader">System</div>
						<a href="index.php?page=updates" class="ctlAltusiaNavigationItem">Check For Updates</a>
						<a href="index.php?page=diagnostics" class="ctlAltusiaNavigationItem">Diagnostics</a>
						<a href="index.php?page=details" class="ctlAltusiaNavigationItem">Installation Details</a>
					</div>
				</td>
				<td>
					<div class="ctlAltusiaPanelTitle">
						<div class="row">
							<div class="col-md-4">
								<div style="padding: 20px 30px;">
									Dashboard
								</div>
							</div>
							<div class="col-md-8">
								<div style="text-align: right;">

									<a class="ctlAltusiaPanelLogoutButton" href="account.logout.php" rel="tooltip" title="Logout" data-placement="bottom">
										<span class="glyphicon glyphicon-off"></span>
									</a>
									<a class="ctlAltusiaPanelLogoutButton" href="../" rel="tooltip" title="View Front End" data-placement="bottom">
										<span class="glyphicon glyphicon-globe"></span>
									</a>
								</div>
							</div>
						</div>
					</div>

					<div class="ctlAltusiaBody">