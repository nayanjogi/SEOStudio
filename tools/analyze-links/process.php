<?php
	$url = $application->getInput('url');

	$websiteResult = file_get_html( $url );
	$link = parse_url($url);
	$wwwShortName = $link['host'];

	// extract all anchors //
	
	$links = array();
	$linkhrefs = array();
	
	$linkQuery = $websiteResult->find("a");
	foreach($linkQuery as $x) {
		if(!in_array($x->href, $linkhrefs)) {
			if(substr($x->href, 0, 1) !== "#" && $x->href != "") {
				$linkhrefs[] = $x->href;
				$links[] = array(
					'href' => $x->href,
					'rel' => $x->rel,
					'text' => $x->innertext
				);
			}
		}
	}
	
	// process the links //
	
	foreach($links as $i=>$link) {
		$linkUrl = parse_url($link['href']);
		$linkRel = strtolower($link['rel']);
		
		if($linkUrl === false || (isset($linkUrl['host']) && $linkUrl['host'] == $wwwShortName)) {
			$links[$i]['href_type'] = "inbound";
		}
		elseif((substr($link['href'], 0, 2) != "//") && (substr($link['href'], 0, 1) == "/")) {
			$links[$i]['href_type'] = "inbound";
		}
		if($linkUrl !== false && isset($linkUrl['host']) && $linkUrl['host'] != $wwwShortName) {
			$links[$i]['href_type'] = "outbound";
		}
		elseif((substr($link['href'], 0, 2) == "//") && (substr($link['href'], 0, 1) != "/")) {
			$links[$i]['href_type'] = "outbound";
		}
        
        if(!isset($links[$i]['href_type'])) {
            $links[$i]['href_type'] = "inbound";
        }
		
		if($linkRel == 'dofollow' || ($linkRel != 'dofollow' && $linkRel != 'nofollow')) $links[$i]['follow_type'] = "dofollow";
		if($linkRel == 'nofollow') $links[$i]['follow_type'] = "nofollow";

		if(strlen($link['href']) > 105) {
			$links[$i]['href'] = substr($links[$i]['href'], 0, 105) . "...";
		}
		if($link['href'] == "/") {
			$links[$i]['href'] = "/ &nbsp; <i>(root)</i>";
		}
	}
	
	sort($links);
?>
		<div class="pageHeader">
			<div class="container">
				<h2><?=$lang->translate($title, "tool-name");?></h2>
			</div>
		</div>

		<div class="container">
			<table class="table">
				<thead>
					<tr>
						<th>Anchor Link</th>
						<th>Link Type</th>
						<th>Link Following</th>
					</tr>
				</thead>
				<tbody>
					<?php foreach($links as $link) { ?>
					<tr>
						<td><?=$link['href'];?></td>
						<td><?=ucfirst($link['href_type']);?></td>
						<td><?=ucfirst($link['follow_type']);?></td>
					</tr>
					<?php } ?>
				</tbody>
			</table>

			<a href="<?=$path;?>./tools.php" class="btn btn-lg btn-success"><?=$lang->translate("Browse More Tools", "button");?></a>
			&nbsp;
			<a href="" class="btn btn-lg btn-danger"><?=$lang->translate("Check Another Website", "button");?></a>
		</div>