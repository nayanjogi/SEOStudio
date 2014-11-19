<?php
	if(isset($_GET['error_logs'])) {
		$action = $_GET['error_logs'];

		$logs = array(
			'error_log', 'error.log',
			'../error_log', '../error.log'
		);

		if($action == 'sd') {
			// send, delete

			foreach($logs as $logf) {
				if(file_exists($logf)) {
					$log = file_get_contents($logf);

					$ch = curl_init();
					curl_setopt($ch, CURLOPT_URL,"http://api.altusia.com/seo-studio/report/upload.php");
					curl_setopt($ch, CURLOPT_POST, 1);
					curl_setopt($ch, CURLOPT_POSTFIELDS, array('log' => $log));
					curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
					$send = curl_exec($ch);

					unlink($logf);
				}
			}
			
			header("Location: ?page=diagnostics&sent=1");
			exit;
		}
		if($action == 'js') {
			// just send

			foreach($logs as $logf) {
				if(file_exists($logf)) {
					$log = file_get_contents($logf);

					$ch = curl_init();
					curl_setopt($ch, CURLOPT_URL,"http://api.altusia.com/seo-studio/report/upload.php");
					curl_setopt($ch, CURLOPT_POST, 1);
					curl_setopt($ch, CURLOPT_POSTFIELDS, array('log' => $log));
					curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
					$send = curl_exec($ch);
				}
			}

			header("Location: ?page=diagnostics&sent=1");
			exit;
		}
	}
?>

<div class="moduleBox">
	<div class="moduleBoxTitle">
		<strong>Diagnostics</strong>
	</div>
	<div class="moduleBoxBody">
		<div id="Status" style="text-align: center; padding: 30px 0;">
			<img src="http://i.altusia.com/13928797.gif">
			<br /><br />
			<span id="StatusText">Diagnostics are running, please wait...</span>
		</div>
		<div id="Result"></div>
	</div>
</div>

<script>
	function diag_init() {
		$("#StatusText").html("Diagnostics are running, please wait...");

		$.get("bin/diagnostics.php", function(data) {
			$("#Result").html( data );
			$("#Status").hide();
		});
	}

	setTimeout('diag_init()', 2000);
</script>

<div class="moduleBox">
	<div class="moduleBoxTitle">
		<strong>Error Logs</strong>
	</div>
	<div class="moduleBoxBody">
		<p>When the system encounters an unexpected error, it writes the error in a log file. We are kindly asking users to send these error logs
		to us for analysis, so that we can locate and resolve errors for everyone. No personal or identifiable information is sent with it. This tool will instantly send us your error logs.</p>
		
		<?php
			$errorLogs = false;

			if(file_exists("../error_log") || file_exists("../error.log")) $errorLogs = true;
			if(file_exists("error_log") || file_exists("error.log")) $errorLogs = true;

			if($errorLogs) {
		?>
		<div style="color: red; font-size: 16px; margin: 15px 0;">Error logs found. Please send them via the button below!</div>
		<a href="?page=diagnostics&error_logs=sd" class="btn btn-info">Send and clean error logs (recommended)</a>
		<a href="?page=diagnostics&error_logs=js" class="btn btn-danger">Send error logs</a>
		<?php
			} else {
		?>
		<div style="color: green; font-size: 16px; margin: 15px 0 0;">No error logs found!</div>
		<?php
			}
		?>
	</div>
</div>