<?php
	$directLink = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
	$directLink = str_replace(array(
		"/admin/index.php", "/admin/", "?closeRate=1", "?clear=1"
	), "", $directLink);
	$directLink .= "/admin/bin/auto_update.php";
	$directLink = urlencode($directLink);

	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, "http://api.webfector.com/public/76a3abd/update.php?v=" . $db->read("Version") . "&token=" . $db->read("Token") . "&direct=" . $directLink);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 15);
	curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.13) Gecko/20080311 Firefox/2.0.0.13');
	$data = curl_exec($ch); // @patch 8/15/2014 : fix error, missing curl_exec_follow

	function time_elapsed_string($ptime) {
	    $etime = time() - $ptime;

	    if ($etime < 1)
	    {
	        return '0 seconds';
	    }

	    $a = array( 12 * 30 * 24 * 60 * 60  =>  'year',
	                30 * 24 * 60 * 60       =>  'month',
	                24 * 60 * 60            =>  'day',
	                60 * 60                 =>  'hour',
	                60                      =>  'minute',
	                1                       =>  'second'
	                );

	    foreach ($a as $secs => $str)
	    {
	        $d = $etime / $secs;
	        if ($d >= 1)
	        {
	            $r = round($d);
	            return $r . ' ' . $str . ($r > 1 ? 's' : '') . ' ago';
	        }
	    }
	}

	if(isset($_GET['clear'])) {
		file_put_contents("../data/alerts.json", "[]");
		header("Location: index.php");
		exit;
	}

	if(isset($_SESSION['Outdated'])) {
		$lastLogin = $_SESSION['Outdated'];
		
		$clientRecords = file_get_contents("../data/client-data.json");
		$clientRecords = array_reverse(json_decode($clientRecords, true));

		$missed = 0;

		foreach($clientRecords as $row) {
			if($row['Time'] > $lastLogin) {
				$missed++;
			}
		}

		unset($clientRecords);

		if($missed > 0) {
			$db->alert("There have been $missed tool uses since you were last online.", "#E7F4E5");
			unset($_SESSION['Outdated']);
		}
	}
?>

	<?php
		if($db->read("Rate Popup") != "Closed") {
	?>
	<div class="pleaseRateBox">
		<h3>Don't forget to rate!</h3>
		<p>
			I have put a lot of time and hard work into this application.
			Giving me an honest rating on CodeCanyon helps others determine the quality of my software, and tells me how I'm doing.
			Please rate this product whenever you can.
		</p>
		<p style="margin-top: 25px;">
			<a target="_blank" href="http://codecanyon.net/downloads" class="btn btn-info btn-sm">Rate Item</a>
			<a href="?closeRate=1" class="btn btn-default btn-sm">Close</a>
		</p>
	</div>
	<?php
		}
	?>

	<div class="row">
		<div class="col-md-9">

			<div class="moduleBox">
				<div class="moduleBoxTitle">
					<div>
						<a href="?clear=1">Clear</a>
					</div>
					<strong>New Notifications</strong>
				</div>
				<div class="moduleBoxBody">
					<?php
						$alerts = file_get_contents("../data/alerts.json");
						$alerts = json_decode($alerts, true);
					?>

					<div style="max-height: 500px; overflow-y: auto;">
						<?php
							foreach($alerts as $alert) {
								$alertColor = $alert[0];
								$alertText = $alert[1];
								$alertTime = $alert[2];

								$alertColor = str_replace("#e0ecff", "#D4E5F3", $alertColor);

								$ttt = time_elapsed_string($alertTime);
								if($ttt == "0 seconds") $ttt = "right now";
						?>
						<div style="background: <?=$alertColor;?>; color: #222; padding: 14px 20px; font-size: 14px;">
							<div class="pull-right" style="color: gray;">
								<?=$ttt?>
							</div>
							<?=$alertText;?>
						</div>
						<?php
							}

							if(count($alerts) == 0) {
						?>
						<div style="padding: 10px;">
							There are no pending alerts at this time.
						</div>
						<?php
							}
						?>
					</div>
				</div>
			</div>

			<?php

				$total = 0;

				function thumb($done) {
					global $total;

					if(!$done) {
						echo '<div style="font-size: 32px; color: #e65555;">
										<span class="glyphicon glyphicon-thumbs-down"></span>
									</div>';
					}
					else {
						$total++;
						echo '<div style="font-size: 32px; color: #7be655;">
										<span class="glyphicon glyphicon-thumbs-up"></span>
									</div>';
					}

					if($total == 4) return true;
					return false;
				}

				if($db->read("Setup Done") !== "Yes") {
			?>

			<div class="moduleBox">
				<div class="moduleBoxTitle">
					<strong>Studio Setup Checklist</strong>
				</div>
				<div class="moduleBoxBody">
					<div class="checklistItem">
						<table width="100%">
							<tr>
								<td width="150px">
									<strong>Configure it!</strong>
								</td>
								<td>
									<p>Change the <a href="?page=settings">settings</a> of the studio to fit your liking. </p>
								</td>
								<td width="40px">
									<?php
										$done = false;

										if($db->read("Website Name") !== "SEO Studio") $done = true;
										if($db->read("Admin Button") === "Off") $done = true;
										if($db->read("Allow Registration") === "Off") $done = true;
										if($db->read("Email Update Notifications") === "Off") $done = true;

										thumb($done);
									?>
								</td>
							</tr>
						</table>
					</div>
					<div class="checklistItem">
						<table width="100%">
							<tr>
								<td width="150px">
									<strong>Home Page Design</strong>
								</td>
								<td>
									<p>Customize your home page with the <a href="?page=design-homepage">home page designer</a>. </p>
								</td>
								<td width="40px">
									<?php
										$done = false;

										if($db->read("Home Page Head") !== "Search Engine Optimization") $done = true;
										if($db->read("Home Page Body") !== "We have a lot of awesome tools to help with search engine optimization for any website. Check them out on our tools page!") $done = true;
										if($db->read("Home Page Sub") !== "We host more than 30 unique, professional tools.") $done = true;

										thumb($done);
									?>
								</td>
							</tr>
						</table>
					</div>
					<div class="checklistItem">
						<table width="100%">
							<tr>
								<td width="150px">
									<strong>Tool Testing</strong>
								</td>
								<td>
									<p>Go test the Backlink Checker tool, and view it after on the <a href="?page=tool-usage">tool logs</a>.</p>
								</td>
								<td width="40px">
									<?php
										$done = false;

										$clientRecords = file_get_contents("../data/client-data.json");
										$clientRecords = array_reverse(json_decode($clientRecords, true));
										
										$max = 500;
										if(count($clientRecords) < 500) $max = count($clientRecords) - 1;
										
										for($x = 0; $x <= $max; $x++) {
											$record = $clientRecords[$x];

											if($record['Tool'] == "Check Backlinks") {
												$done = true;
											}

										}

										thumb($done);
									?>
								</td>
							</tr>
						</table>
					</div>
					<div class="checklistItem" style="border-bottom: 0px;">
						<table width="100%">
							<tr>
								<td width="150px">
									<strong>Remove Branding</strong>
								</td>
								<td>
									<p>Remove "Webfector" from the copyright label on the <a href="?page=header-footer">footer</a>.</p>
								</td>
								<td width="40px">
									<?php
										$done = false;

										if($db->read("Copyright") !== "© 2014 Webfector") $done = true;

										$a = thumb($done);
										if($a === true) {
											$db->write("Setup Done", "Yes");
										}
									?>
								</td>
							</tr>
						</table>
					</div>
				</div>
			</div>

			<?php } ?>

			<div class="moduleBox">
				<div class="moduleBoxTitle">
					<strong>Upcoming Updates</strong>
				</div>
				<div class="moduleBoxBody">
					<p>As we futher develop this application, we record the changes we make through GitHub. Although our repository is private, you can view the latest commits in real-time at the
					<a href="?page=updates">updates page</a>.
				</div>
			</div>

		</div>
		<div class="col-md-3">
			<div class="moduleBox">
				<div class="moduleBoxTitle">
					<strong>Welcome!</strong>
				</div>
				<div class="moduleBoxBody">
					Hi there. You are logged in as:
					<br /><br />

					<?=$db->read("Admin Email");?>
				</div>
			</div>
			<div class="moduleBox">
				<div class="moduleBoxTitle">
					<strong>My Account</strong>
				</div>
				<div class="moduleBoxBody">
					<a href="?page=my-account">Change Password</a><br />
					<a href="?page=my-account">Change Email</a>
				</div>
			</div>
			<div class="moduleBox">
				<div class="moduleBoxTitle">
					<strong>SEO Studio</strong>
				</div>
				<div class="moduleBoxBody">
					Running version <?=$db->read("Version");?>
				</div>
			</div>
		</div>
	</div>

	<style>
	.checklistItem tr {
		vertical-align: middle;
	}
	</style>