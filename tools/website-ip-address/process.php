<?php
	$url = $application->getInput("url");
    $url = str_ireplace("www.", "", $url);

	$link = parse_url($url);
	$domain = $link['host'];

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

	$ip = getAddresses_www($domain);
	$ip = $ip[count($ip)-1];
	if(!$ip) {
		$ip = getAddresses_www("http://".$domain);
		$ip = $ip[count($ip)-1];

		if(!$ip) $ip = "?";
	}

	$string = $lang->translate("The IP address of %1 is %2.", "variable");
	$string = str_replace("%1", $domain, $string);
	$string = str_replace("%2", "<strong>{$ip}</strong>", $string);
?>
		<div class="pageHeader">
			<div class="container">
				<h2><?=$lang->translate($title, "tool-name");?></h2>
			</div>
		</div>

		<div class="container">
			<?php
				echo $string;
			?>

			<hr />

			<a href="<?=$path;?>./tools.php" class="btn btn-lg btn-success"><?=$lang->translate("Browse More Tools", "button");?></a>
			&nbsp;
			<a href="" class="btn btn-lg btn-danger"><?=$lang->translate("Check Another Website", "button");?></a>
		</div>