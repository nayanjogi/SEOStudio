<?php
	$url = $application->getInput("url");

	function dnsbllookup($ip) {
		$list = array();
		
		$dnsbl_lookup = array(
			"dnsbl-1.uceprotect.net",
			"dnsbl-2.uceprotect.net",
			"dnsbl-3.uceprotect.net",
			"dnsbl.dronebl.org",
			"dnsbl.sorbs.net",
			"spam.dnsbl.sorbs.net",
			"zen.spamhaus.org",
			"bl.spamcop.net",
			"recent.dnsbl.sorbs.net",
			"dnsbl.justspam.org"
		);
		if($ip) {
			$reverse_ip = implode(".",array_reverse(explode(".",$ip)));
			foreach($dnsbl_lookup as $host) {
				if(checkdnsrr($reverse_ip.".".$host.".","A")) {
					$list[] = array(
						"DNSBL" => $host,
						"IP Address" => $reverse_ip,
						"Blacklisted" => true
					);
				}
				else {
					$list[] = array(
						"DNSBL" => $host,
						"IP Address" => $reverse_ip,
						"Blacklisted" => false
					);
				}
			}
		}
		if(isset($list) && $list !== array()) {
			return $list;
		} else {
			return false;
		}
	}
	
	function checkdnsbl($ip) {
		if(isset($ip) && $ip != null) {
			if(filter_var($ip, FILTER_VALIDATE_IP)) {
				return dnsbllookup($ip);
			} else {
				return false;
			}
		}
		else {
			return false;
		}
	}

	function getAddresses($domain) {
	  $records = dns_get_record($domain);
	  $res = array();
	  foreach ($records as $r) {
		if ($r['host'] != $domain) continue; // glue entry
		if (!isset($r['type'])) continue; // DNSSec

		if ($r['type'] == 'A') $res[] = $r['ip'];
		if ($r['type'] == 'AAAA') $res[] = $r['ipv6'];
	  }
	  return $res;
	}

	function getAddresses_www($domain) {
	  $res = getAddresses($domain);
	  if (count($res) == 0) {
		$res = getAddresses('www.' . $domain);
	  }
	  return $res;
	}

	$domain = parse_url($url);
	$domain = str_replace("www.", "", $domain['host']);

	$ip = getAddresses_www($domain);
	$ip = $ip[count($ip)-1];
	if(!$ip) {
		echo "<h3>An error occured!</h3>";
		echo "<p>Failed to translate website/domain into an IP address.</p>";
		echo "<p>Terminating...</p>";
		exit;
	}
	
	$blacklisted = checkdnsbl($ip); // returns an array of all blacklists checked against, and their result.
	
	if($blacklisted === false) {
		echo "<h3>An error occured!</h3>";
		echo "<p>Failed to check blacklist status for this website/domain.</p>";
		echo "<p>Terminating...</p>";
		exit;
	}
	
?>
		<div class="pageHeader">
			<div class="container">
				<h2><?=$lang->translate($title, "tool-name");?></h2>
			</div>
		</div>

		<div class="container">
			<?php foreach($blacklisted as $b) { ?>
			<div class="well well-small" style="margin-bottom: 10px; padding:15px;">
				<table width='100%'>
					<tr>
						<td width='350'><h4 style="margin: 0;"><?=$b["DNSBL"];?></h4></td>
						<td>
							Checking against <?=$domain;?>
						</td>
						<td width='150'>
							<?php if($b['Blacklisted'] == true) { ?><h4 style="margin: 0;"><font color='red'>LISTED</font></h4><?php } else { ?>
							<h4 style="margin: 0;"><font color='green'>UNLISTED</font></h4><?php } ?>
						</td>
					</tr>
				</table>
			</div>
			<?php } ?>

			<hr />

			<a href="<?=$path;?>./tools.php" class="btn btn-lg btn-success"><?=$lang->translate("Browse More Tools", "button");?></a>
			&nbsp;
			<a href="" class="btn btn-lg btn-danger"><?=$lang->translate("Check Another Website", "button");?></a>
		</div>