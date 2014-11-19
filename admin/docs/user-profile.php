<?php
	$username = $_GET['username'];
	$userRow = null;

	$users = file_get_contents("../data/users.json");
	$users = json_decode($users, true);

	$userId = 0;

	foreach($users as $i=>$userRow2) {
		if(strtolower($userRow2['Username']) == strtolower($username)) {
			$userRow = $userRow2;
			$userId = $i;
		}
	}

	if($userRow == null) {
		header("Location: index.php?page=users");
		exit;
	}

	if(!isset($userRow['Email'])) $userRow['Email'] = "None provided";

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

	if(isset($userRow['LastVisit'])) {
		$lastOnline = $userRow['LastVisit'];
		if($lastOnline > (time() - 300)) {
			$lastOnline = "<font color='green'>Online Now</font>";
		}
		else {
			$lastOnline = "<font color='red'>" . time_elapsed_string($lastOnline) . "</font>";
		}
	}
	else {
		$lastOnline = "Unknown";
	}

?>
<div style="padding-left: 20px;">

	<h3 style="margin-bottom: 50px;"><?=$userRow['Username'];?></h3>

	<div class="row">
		<div class="col-md-8">
			<table class="table table-striped">
				<tbody>
					<tr>
						<th width="180px">Email Address</th>
						<td><?=$userRow['Email'];?></td>
					</tr>
					<tr>
						<th>IP Address</th>
						<td><?=$userRow['Address'];?></td>
					</tr>
					<tr>
						<th>Last Online</th>
						<td><?=$lastOnline;?></td>
					</tr>
				</tbody>
			</table>
		</div>
		<div class="col-md-4">
			<div class="moduleBox">
				<div class="moduleBoxTitle">
					<strong>Latest Tools Used</strong>
				</div>
				<div class="moduleBoxBody">
<?php
	$clientRecords = file_get_contents("../data/client-data.json");
	$clientRecords = array_reverse(json_decode($clientRecords, true));

	$showing = 0;

	for($x = 0; $x <= (count($clientRecords)-1); $x++) {
		$record = $clientRecords[$x];

		if($record['Visitor'] == $userRow['Username']) {
			if($showing < 5 && $record['Tool'] != "Check Competition" && $record['Tool'] != "Compare Backlinks" && $record['Tool'] != "Compare PageRank") {
				$showing++;
?>
<div style="font-size: 12px; padding: 10px 7px; border-bottom: 1px solid #ddd;">
	<div style="padding-bottom: 5px;">
		<strong><?=$record['Tool'];?></strong>
	</div>
	<?=$record['Data'];?> (<?=time_elapsed_string($record['Time']);?>)
</div>
<?php
			}
		}
	}
?>
				</div>
			</div>
		</div>
	</div>

	<style>
		tr td tr {
			vertical-align: middle !important;
		}
	</style>
</div>