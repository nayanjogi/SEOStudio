<?php
	require "../../libraries/simple_html_dom.php";

	$url = $_GET['url'];
	$wwwShortName = $_GET['short'];
	$baseName = $_GET['base'];

	$url = str_replace("http_-_", "http://", $url);
	$url = str_replace("https_-_", "https://", $url);

	$wwwShortName = str_replace("http_-_", "http://", $wwwShortName);
	$wwwShortName = str_replace("https_-_", "https://", $wwwShortName);

	$baseName = str_replace("http_-_", "http://", $baseName);
	$baseName = str_replace("https_-_", "https://", $baseName);


	$wwwShortName = str_replace("www.", "", $wwwShortName);

	$websiteResult = file_get_html( $url );

	if(!$websiteResult) {
		exit(json_encode(array()));
	}
	
	// extract all anchors //
	
	$links = array();
	$linkhrefs = array();
	
	$linkQuery = $websiteResult->find("a");
	if(!$linkQuery) {
		exit(json_encode(array()));
	}
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

		if(isset($linkUrl['host'])) {
			$linkUrl['host'] = str_replace("www.", "", $linkUrl['host']);
		}
		
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
		if(!isset($links[$i]['href_type']))
			$links[$i]['href_type'] = 'outbound';
		
		if($linkRel == 'dofollow' || ($linkRel != 'dofollow' && $linkRel != 'nofollow')) $links[$i]['follow_type'] = "dofollow";
		if($linkRel == 'nofollow') $links[$i]['follow_type'] = "nofollow";

		if(strlen($link['href']) > 105) {
			$links[$i]['href'] = substr($links[$i]['href'], 0, 105) . "...";
		}
		if($link['href'] == "/") {
			$links[$i]['href'] = "/";
		}
	}
	
	sort($links);

	$return = array();

	foreach($links as $link) {
		$href = $link['href'];

		if($link['href_type'] == 'inbound') {
			if(substr($href, 0, 2) === "//") {
				$href = null;
			}
			elseif(substr($href, 0, 1) == "/") {
				$href = $baseName . $href;
			}

			if(substr($link['href'], -1) !== "/" && (substr($href, 0, 8) === "https://" || substr($href, 0, 7) === "http://")) {
				$href = $href . "/";
			}

			$href = str_replace("http://", "http_-_", $href);
			$href = str_replace("https://", "https_-_", $href);


			if($href !== null && !in_array($href, $return)) 
				$return[] = $href;
		}
	}

	echo json_encode($return);
?>