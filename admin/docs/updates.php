<?php
	if(isset($_GET['update_action'])) {
		$epath = "../";
		require "../bin/main.php";

		if($_GET['update_action'] == 'check') {
			sleep(2);

			$currentVersion = $db->read("Version");

			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, "http://api.altusia.com/seo-studio/latest-version?php");
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
			curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 15);
			curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.13) Gecko/20080311 Firefox/2.0.0.13');
			$data = curl_exec($ch);
			
			$updates = json_decode($data, true);
			if($updates === null) {
				exit("Failed to connect to update checking API.");
			}
			
			if($updates['Success'] == true) {
				$v = $updates['Version'];

				$currentVersion = str_replace(".", "", $currentVersion);
				$v = str_replace(".", "", $v);

				if(intval($currentVersion) < intval($v)) {
					exit('download_info');
				}
				else {
					exit('
						<div style="text-align: center; padding: 17px 0 18px;">
							<div style="font-size: 55px; color: green;">
								<span class="glyphicon glyphicon-thumbs-up"></span>
							</div>
							There are no new updates available.
						</div>
					');
				}
			}
			else {
				exit("Received an invalid response from the Altusia Update API.");
			}
		}

		if($_GET['update_action'] == 'getinfo') {
			sleep(2);

			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, "http://api.altusia.com/seo-studio/latest-version-details?php");
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
			curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 15);
			curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.13) Gecko/20080311 Firefox/2.0.0.13');
			$data = curl_exec($ch);
			
			exit($data);
		}

		if($_GET['update_action'] == 'upcoming') {
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, "http://www.altusia.com/git/list.php?php");
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
			curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 15);
			curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.13) Gecko/20080311 Firefox/2.0.0.13');
			$data = curl_exec($ch);
			
			$updates = json_decode($data, true);
			if($updates === null) {
				exit("Failed to fetch the latest commits.");
			}

			echo '<table width="100%" class="table table-striped">';
			echo '<thead>';
			echo '<th width="120px" style="text-align: center;">ID</th><th>Description</th><th width="140px" style="text-align: center;">Time</th>';
			echo '</thead><tbody>';

			foreach($updates as $commit) {
?>
<tr>
	<td style="text-align: center;"><?=$commit['Id'];?></td>
	<td><?=$commit['Message'];?></td>
	<td style="text-align: center;"><?=$commit['Time'];?></td>
</tr>
<?php
			}

			echo '</tbody></table>';
			exit;
		}
	}
?>
	<div class="row">
		<div class="col-md-9">

			<div class="moduleBox">
				<div class="moduleBoxTitle">
					<strong>New Updates</strong>
				</div>
				<div class="moduleBoxBody">
					<div id="Status" style="text-align: center; padding: 30px 0;">
						<img src="http://i.altusia.com/13928797.gif">
						<br /><br />
						<span id="StatusText">Please Wait</span>
					</div>
					<div id="Result"></div>
				</div>
			</div>

			<div class="moduleBox">
				<div class="moduleBoxTitle">
					<strong>In-Development Updates</strong>
				</div>
				<div class="moduleBoxBody">
					<div id="Upcoming">
						<img src="http://i.altusia.com/13928797.gif">
					</div>
				</div>
			</div>

		</div>
		<div class="col-md-3">
			<div class="moduleBox">
				<div class="moduleBoxTitle">
					<strong>Current Version</strong>
				</div>
				<div class="moduleBoxBody">
					<p style="padding: 15px; margin:0px;">You are running version <?=$db->read("Version");?></p>
				</div>
			</div>
			<div class="moduleBox">
				<div class="moduleBoxTitle">
					<strong>Update Subscription</strong>
				</div>
				<div class="moduleBoxBody">
					<!-- Begin MailChimp Signup Form -->
					<link href="//cdn-images.mailchimp.com/embedcode/classic-081711.css" rel="stylesheet" type="text/css">
					<style type="text/css">
						#mc_embed_signup{background:#fff; clear:left; font:14px Helvetica,Arial,sans-serif;  width:220px;}
						/* Add your own MailChimp form style overrides in your site stylesheet or in this style block.
						   We recommend moving this block and the preceding CSS link to the HEAD of your HTML file. */
					</style>
					<div style="margin-top: 0px;" id="mc_embed_signup">
					<form style="margin-top: 0px;" action="http://altusia.us8.list-manage.com/subscribe/post?u=d0a2eb9cdccefd78cf61923f1&amp;id=0e702849e2" method="post" id="mc-embedded-subscribe-form" name="mc-embedded-subscribe-form" class="validate" target="_blank" novalidate>
						<h2 style="margin-top: 0px;">Receive an email when SEO Studio is updated</h2>
					<div class="indicates-required"><span class="asterisk">*</span> indicates required</div>
					<div class="mc-field-group">
						<label for="mce-EMAIL">Email Address  <span class="asterisk">*</span>
					</label>
						<input type="email" value="" name="EMAIL" class="required email" id="mce-EMAIL">
					</div>
					<div class="mc-field-group">
						<label for="mce-FNAME">First Name </label>
						<input type="text" value="" name="FNAME" class="" id="mce-FNAME">
					</div>
					<div class="mc-field-group">
						<label for="mce-LNAME">Last Name </label>
						<input type="text" value="" name="LNAME" class="" id="mce-LNAME">
					</div>
						<div id="mce-responses" class="clear">
							<div class="response" id="mce-error-response" style="display:none"></div>
							<div class="response" id="mce-success-response" style="display:none"></div>
						</div>    <!-- real people should not fill this in and expect good things - do not remove this or risk form bot signups-->
					    <div style="position: absolute; left: -5000px;"><input type="text" name="b_d0a2eb9cdccefd78cf61923f1_0e702849e2" tabindex="-1" value=""></div>
					    <div class="clear"><input type="submit" value="Subscribe" name="subscribe" id="mc-embedded-subscribe" class="button"></div>
					</form>
					</div>

					<!--End mc_embed_signup-->
				</div>
			</div>
		</div>
	</div>

	<script>

		function AutoReloadUpcoming() {
			$.get("docs/updates.php?update_action=upcoming", function(data) {
				$("#Upcoming").html( data );
			});

			setTimeout('AutoReloadUpcoming()', 5000);
		}

		$(document).ready(function() {
			$("#StatusText").html("Checking for updates...");

			$.get("docs/updates.php?update_action=upcoming", function(data) {
				$("#Upcoming").html( data );
				AutoReloadUpcoming();
			});

			$.get("docs/updates.php?update_action=check", function(data) {
				if(data == "download_info") {
					$("#StatusText").html("Downloading update information...");

					$.get("docs/updates.php?update_action=getinfo", function(data) {
						
						$("#Result").html( data );
						$("#Status").hide();
					});
				}
				else {
					$("#Result").html( data );
						$("#Status").hide();
				}
			});
		});

	</script>