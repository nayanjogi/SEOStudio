<?php
	$order = "TimeDescending";
	if(isset($_POST['order'])) $order = $_POST['order'];

	$perpage = 100;
	if(isset($_POST['perpage'])) $perpage = $_POST['perpage'];

	$q = "";
	if(isset($_POST['q'])) $q = $_POST['q'];

	$page = 1;
	if(isset($_POST['p'])) $page = $_POST['p'];

	$results = array();

	// calculate, read, and order the results

	$clientRecords = file_get_contents("../data/client-data.json");

	if($order == "TimeDescending") $clientRecords = array_reverse(json_decode($clientRecords, true));
	if($order != "TimeDescending") $clientRecords = json_decode($clientRecords, true);
	
	for($x = 0; $x <= (count($clientRecords)-1); $x++) {
		$record = $clientRecords[$x];

		if($order == "IPAscending" || $order == "IPDescending") {
			if(!isset($results[$record['Visitor']])) $results[$record['Visitor']] = array();
			$results[$record['Visitor']][] = $record;
		}
		if($order == "ToolAscending" || $order == "ToolDescending") {
			if(!isset($results[$record['Tool']])) $results[$record['Tool']] = array();
			$results[$record['Tool']][] = $record;
		}
		if($order == "InputAscending" || $order == "InputDescending") {
			if(!isset($results[$record['Data']])) $results[$record['Data']] = array();
			$results[$record['Data']][] = $record;
		}

		if($order == "TimeDescending") $results[] = $record;
		if($order == "TimeAscending") $results[] = $record;
	}

	if($order == "IPAscending") ksort($results);
	if($order == "IPDescending") {
		ksort($results);
		$results = array_reverse($results);
	}
	if($order == "ToolAscending") ksort($results);
	if($order == "ToolDescending") {
		ksort($results);
		$results = array_reverse($results);
	}
	if($order == "InputAscending") ksort($results);
	if($order == "InputDescending") {
		ksort($results);
		$results = array_reverse($results);
	}

	function CriteriaMet($row, $qt) {
		$ddd = $row['Data'];
		$vvv = $row['Visitor'];
		$ttt = $row['Tool'];

		$qt = trim($qt);

		if(stripos($ddd, $qt) !== false) return true;
		if(stripos($ttt, $qt) !== false) return true;
		if(stripos($vvv, $qt) !== false) return true;
	}


	$results2 = array();
	foreach($results as $res) {
		if($order != "TimeAscending" && $order != "TimeDescending") {
			foreach($res as $r) {
				if($q != "") {
					if(CriteriaMet($r, $q)) $results2[] = $r;
				}
				else {
					$results2[] = $r;
				}
			}
		}
		else {
			if($q != "") {
				if(CriteriaMet($res, $q)) $results2[] = $res;
			}
			else {
				$results2[] = $res;
			}
		}
	}

	$results = $results2;
?>
<form action="" method="POST">
	<div class="moduleBox">
		<div class="moduleBoxTitle">
			<strong>Sort Results</strong>
		</div>
		<div class="moduleBoxBody">
			<div>
				<table width="100%">
					<tr style="vertical-align: middle !important;">
						<td width="70px">
							Search:
						</td>
						<td width="200px">
							<input type="text" name="q" class="form-control" value="<?=$q;?>">
						</td>
						<td width="20px">
							&nbsp;
						</td>
						<td width="80px">
							Order By:
						</td>
						<td>
							<select name="order" class="form-control">
								<option <?php if($order == "TimeAscending") { ?>selected <?php } ?>value="TimeAscending">Time Ascending (oldest first)</option>
								<option <?php if($order == "TimeDescending") { ?>selected <?php } ?>value="TimeDescending">Time Descending (newest first)</option>
								<option <?php if($order == "IPAscending") { ?>selected <?php } ?>value="IPAscending">IP Ascending</option>
								<option <?php if($order == "IPDescending") { ?>selected <?php } ?>value="IPDescending">IP Descending</option>
								<option <?php if($order == "ToolAscending") { ?>selected <?php } ?>value="ToolAscending">Tool Ascending</option>
								<option <?php if($order == "ToolDescending") { ?>selected <?php } ?>value="ToolDescending">Tool Descending</option>
								<option <?php if($order == "InputAscending") { ?>selected <?php } ?>value="InputAscending">Input Ascending (a-z)</option>
								<option <?php if($order == "InputDescending") { ?>selected <?php } ?>value="InputDescending">Input Descending (z-a)</option>
							</select>
						</td>
						<td width="20px">
							&nbsp;
						</td>
						<td width="90px">
							Per Page:
						</td>
						<td width="90px">
							<select name="perpage" class="form-control">
								<option <?php if($perpage == 10) { ?>selected <?php } ?>value="10">10</option>
								<option <?php if($perpage == 25) { ?>selected <?php } ?>value="25">25</option>
								<option <?php if($perpage == 50) { ?>selected <?php } ?>value="50">50</option>
								<option <?php if($perpage == 100) { ?>selected <?php } ?>value="100">100</option>
								<option <?php if($perpage == 200) { ?>selected <?php } ?>value="200">200</option>
								<option <?php if($perpage == 500) { ?>selected <?php } ?>value="500">500</option>
								<option <?php if($perpage == 1000) { ?>selected <?php } ?>value="1000">1000</option>
							</select>
						</td>
						<td width="20px">
							&nbsp;
						</td>
						<td width="60px">
							<input type="submit" value="Load" class="btn btn-primary">
						</td>
					</tr>
				</table>
			</div>
		</div>
	</div>
	<div class="moduleBox">
		<div class="moduleBoxTitle">
			<strong>Results</strong>
		</div>
		<div class="moduleBoxBody">
			<table class="table table-striped">
				<thead>
					<tr>
						<th><strong>Visitor Query</strong></th>
						<th><strong>Queried Tool</strong></th>
						<th><strong>Visitor ID</strong></th>
						<th><strong>Time</strong></th>
					</tr>
				</thead>
				<tbody>
			<?php
				$users = file_get_contents("../data/users.json");
				$users = json_decode($users, true);

				$clientRecords = $results;
				
				$max = $perpage * $page;
				if(count($clientRecords) < $perpage) $max = count($clientRecords) - 1;
				
				$start = ($perpage * $page) - $perpage;

				for($x = $start; $x <= $max; $x++) {
					if(isset($clientRecords[$x])) {
					$record = $clientRecords[$x];

					$userExists = false;
					foreach($users as $userRow) {
						if(strtolower($userRow['Username']) == strtolower($record['Visitor'])) {
							$userExists = true;
						}
					}
					if($userExists !== false) {
						$record['Visitor'] = "<a href='?page=user-profile&username=" . $record['Visitor'] . "'>" . $record['Visitor'] . "</a>";
					}
			?>
					<tr>
						<td><?=$record['Data'];?></td>
						<td><?=$record['Tool'];?></td>
						<td><?=$record['Visitor'];?></td>
						<td><?=date("m/d/Y g:i A", $record['Time']);?></td>
					</tr>
			<?php
					}
				}
			?>
				</tbody>
			</table>
		</div>
	</div>
	<div class="moduleBox">
		<div class="moduleBoxTitle">
			<strong>Pages</strong>
		</div>
		<div class="moduleBoxBody">
			<?php
				$pages = ceil(count($clientRecords) / $perpage);
			?>
			<?php
				if($page > 1) echo "<a href='javascript:;' onclick='Paginate(" . ($page - 1) . ");'>Previous</a> &nbsp;";
				if($page <= 1) echo "<a onclick=''>Previous</a> &nbsp;";
			?>
			&nbsp; &nbsp; 
			Page <strong><?=$page;?> of <?=$pages;?></strong>
			&nbsp; &nbsp; &nbsp; 
			<?php
				if($page < $pages) echo "<a href='javascript:;' onclick='Paginate(" . ($page + 1) . ");'>Next</a> &nbsp;";
				if($page >= $pages) echo "<a onclick=''>Next</a> &nbsp;";
			?>
		</div>
	</div>
	<input type="hidden" name="p" value="1" id="PGNMV">
</form>

<script>
	function Paginate(pg) {
		$("#PGNMV").val(pg.toString());
		document.forms[0].submit();
	}
</script>