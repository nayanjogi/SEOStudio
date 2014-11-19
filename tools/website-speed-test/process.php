<?php
	$url = $application->getInput("url");

	$downloads = array();
	$download_history = array();

	ini_set('max_execution_time', 60*5);

	function newTimedPacket($urlf) {
		$time_start = microtime(true);

		$f = file_get_html($urlf);

		$time_end = microtime(true);
		$time = $time_end - $time_start;
		
		return array($f, $time);
	}

	function newTimedSubPacket($urlf) {
		$time_start = microtime(true);

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $urlf);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
		curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.13) Gecko/20080311 Firefox/2.0.0.13');
		curl_exec_follow($ch);
		curl_close($ch);

		$time_end = microtime(true);
		$time = $time_end - $time_start;
		
		return array(true, $time);
	}

	$main = newTimedPacket($url);
	$time = $main[1];
	$mainf = $main[0];

	$urldata = parse_url($url);
	$fixed_url = $urldata['scheme'] . "://" . $urldata['host'];

	foreach($mainf->find('link') as $element) {
		$href = $element->href;

		if(isset($href) && $href) {
			if(substr($href, 0, 7) == "http://") { }
			elseif(substr($href, 0, 2) == "//") {
				$href = "http:" . $href;
			}
			elseif(substr($href, 0, 1) == "/") {
				$href = $fixed_url . $href;
			}

			if($time < (ini_get('max_execution_time')-4)) {
				if(!in_array($href, $download_history)) {
					$secondary = newTimedSubPacket($href);
					$timetaken = $secondary[1];
					$time = $time + $timetaken;
					$downloads[] = array(
						'Type' => 'Resource Link',
						'Location' => $href,
						'Time' => round($timetaken, 3)
					);
					$download_history[] = $href;
				}
			}
			else {
				$time = (ini_get('max_execution_time')-4);
			}
		}
	}

	foreach($mainf->find('script') as $element) {
		$href = $element->src;

		if(isset($href) && $href) {
			if(substr($href, 0, 7) == "http://") { }
			elseif(substr($href, 0, 2) == "//") {
				$href = "http:" . $href;
			}
			elseif(substr($href, 0, 1) == "/") {
				$href = $fixed_url . $href;
			}

			if($time < (ini_get('max_execution_time')-4)) {
				if(!in_array($href, $download_history)) {
					$secondary = newTimedSubPacket($href);
					$timetaken = $secondary[1];
					$time = $time + $timetaken;
					$downloads[] = array(
						'Type' => 'Script',
						'Location' => $href,
						'Time' => round($timetaken, 3)
					);
					$download_history[] = $href;
				}
			}
			else {
				$time = (ini_get('max_execution_time')-4);
			}
		}
	}

	foreach($mainf->find('img') as $element) {
		$href = $element->src;

		if(isset($href) && $href) {
			if(substr($href, 0, 7) == "http://") { }
			elseif(substr($href, 0, 2) == "//") {
				$href = "http:" . $href;
			}
			elseif(substr($href, 0, 1) == "/") {
				$href = $fixed_url . $href;
			}

			if($time < (ini_get('max_execution_time')-4)) {
				if(!in_array($href, $download_history)) {
					$secondary = newTimedSubPacket($href);
					$timetaken = $secondary[1];
					$time = $time + $timetaken;
					$downloads[] = array(
						'Type' => 'Image',
						'Location' => $href,
						'Time' => round($timetaken, 3)
					);
					$download_history[] = $href;
				}
			}
			else {
				$time = (ini_get('max_execution_time')-4);
			}
		}
	}

	$average = round($time, 4);

	// done
?>

		<div class="pageHeader">
			<div class="container">
				<h2><?=$lang->translate($title, "tool-name");?></h2>
			</div>
		</div>

		<div class="container">
			
			<div style="background: #fff; margin-bottom: 30px; padding: 20px;">
				<?php
					$str = $lang->translate("%1 took %2 seconds to load all resources", "variable");
					$str = str_replace("%1", "<strong>{$url}</strong>", $str);
					$str = str_replace("%2", "<strong>{$average}</strong>", $str);

					echo $str;
				?>
			</div>

			<table class="table table-striped" style="margin-bottom: 30px;">
				<thead>
					<tr>
						<th><?=$lang->translate("Format", "thead");?></th>
						<th><?=$lang->translate("URL", "thead");?></th>
						<th><?=$lang->translate("Download Time", "thead");?></th>
					</tr>
				</thead>
				<tbody>
					<?php
						foreach($downloads as $row) {
					?>
					<tr>
						<td><?=$lang->translate($row['Type'], "format");?></td>
						<td><?=$row['Location'];?></td>
						<td><?=$row['Time'];?> <?=$lang->translate("seconds", "unit");?></td>
					</tr>
					<?php
						}
					?>
				</tbody>
			</table>

			<a href="<?=$path;?>./tools.php" class="btn btn-lg btn-success"><?=$lang->translate("Browse More Tools", "button");?></a>
			&nbsp;
			<a href="" class="btn btn-lg btn-danger"><?=$lang->translate("Check Another Website", "button");?></a>
		</div>