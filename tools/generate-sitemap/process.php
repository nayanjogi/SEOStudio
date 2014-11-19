<?php
	$url = $application->getInput("url");

	$crawling = $lang->translate("Crawling...", "status");
	$starting = $lang->translate("Starting...", "status");
	$finishing = $lang->translate("Creating sitemap...", "status");

	$link = parse_url($url);

    if(!isset($link['host']) || !isset($link['scheme'])) exit("Invalid domain!");

	$shortName = $link['host'];
	$baseName = $link['scheme'] . "://" . $link['host'];

	$shortName = str_replace("http://", "http_-_", $shortName);
	$shortName = str_replace("https://", "https_-_", $shortName);

	$baseName = str_replace("http://", "http_-_", $baseName);
	$baseName = str_replace("https://", "https_-_", $baseName);

	$url2 = str_replace("http://", "http_-_", $url);
	$url2 = str_replace("https://", "https_-_", $url2);

	if(!isset($_POST['doFormPostbackSitemap'])) {
?>
	<div class="pageHeader">
		<div class="container">
			<h2><?=$lang->translate($title, "tool-name");?></h2>
		</div>
	</div>

	<div class="container">

		<h3><?=$url;?></h3>

		<hr />

		<p>
			<strong><?=$lang->translate("Status:", "thead");?></strong>
			&nbsp;
			<span id="status">
				<?=$lang->translate("JavaScript is required");?>
			</span>

			<br /><br />

			<strong><?=$lang->translate("Pages:", "thead");?></strong>
			&nbsp;
			<span id="pages">
				0
			</span>

			<br /><br />

			<strong><?=$lang->translate("Threads:", "thead");?></strong>
			&nbsp;
			<span id="threads">
				0
			</span>

			<br /><br />

			<strong><?=$lang->translate("Queue:", "thead");?></strong>
			&nbsp;
			<span id="queue">
				0
			</span>
		</p>

	</div>
	
	<form action="" method="POST">
		<input type="hidden" name="doFormPostback" value="<?=md5($title);?>">
		<input type="hidden" name="doFormPostbackSitemap" value="<?=md5($title);?>">
		<input type="hidden" name="urls" id="inputUrls">
		<input type="hidden" name="url" value="<?=$url;?>">
	</form>

	<script>

		var urls = new Array();
		var crawled_num = 0;
		var alreadyNoticed = false;
		var work = true;
		var active = 0;
		var finishing1 = false;
		var finishing2 = false;
		var crawl_queue = new Array();

		function crawl(crawlurl) {
			active = active + 1;
			$("#status").html("<?=$crawling;?>");

			if(urls.length > 5000) {
				if(alreadyNoticed == false) {
					alreadyNoticed = true;
					work = false;
					alert("You can't index more than 5000 pages with this tool!");
				}
			}
			else {
				if(work == true) {
					$.getJSON("<?=$path;?>./tools/generate-sitemap/crawlbot.php?base=<?=$baseName;?>&short=<?=$shortName;?>&url=" + crawlurl, function(data){
						active = active - 1;
						$.each(data, function (index, value) {
							if(urls.indexOf(value) == -1) {
								urls.push(value);
								console.log("Crawled: " + value);
								crawled_num = crawled_num + 1;

								crawl_queue.push(value);
							}
						});
					});
				}
			}
		}

		function buildSitemap() {
			$("#status").html("<?=$finishing;?>");
			var newInputValue = urls.join();
			urls = new Array();
			$("#inputUrls").val(newInputValue);
			newInputValue = "";

			document.forms[0].submit();
		}

		function crawlWorker() {
			if(active < 10) {
				var newThreads = 10 - active;

				for(index = 0; index < newThreads; ++index) {
					var urlToCrawl = crawl_queue[0];
					if(urlToCrawl) {
						crawl(urlToCrawl);
						crawl_queue.shift();
					}
				}
			}

			if(finishing1 == true && (crawl_queue.length > 0 || active > 0)) {
				finishing1 = false;
				finishing2 = false;
			}

			if(crawl_queue.length == 0 && active == 0) {
				if(finishing1 == false && finishing2 == false) {
					finishing1 = true;
				}
				else if(finishing1 == true && finishing2 == false) {
					$("#status").html("<?=$finishing;?>");
					setTimeout("buildSitemap();", 1500);
					finishing2 = true;
				}
			}

			setTimeout('crawlWorker()', 1000);
		}

		function init() {
			$("#status").html("<?=$starting;?>");

			setTimeout('crawl("<?=$url2;?>")', 1000);
		}

		function refreshData() {
			$("#threads").html(active.toString());
			$("#pages").html(crawled_num.toString() + " / 5000");
			$("#queue").html(crawl_queue.length.toString());

			setTimeout('refreshData()', 200);
		}
		setTimeout('refreshData()', 100);
		setTimeout('crawlWorker()', 1000);

		init();

	</script>

<?php
	}
	else {
		$urls = $_POST['urls'];
		$urls = explode(",", $urls);

		$sitemap = '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:schemaLocation="http://www.sitemaps.org/schemas/sitemap/0.9 http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd">' . PHP_EOL;

		foreach($urls as $url) {
			if(strlen($url) > 1) {

				$url = str_replace("http_-_", "http://", $url);
				$url = str_replace("https_-_", "https://", $url);

				$sitemap .= '<url>' . PHP_EOL;
				$sitemap .= '  <loc>' . $url . '</loc>' . PHP_EOL;
				$sitemap .= '</url>' . PHP_EOL;
			}
		}

		$sitemap .= '</urlset>';
		$sitemap = str_replace(">", "&gt;", $sitemap);
		$sitemap = str_replace("<", "&lt;", $sitemap);
?>
	<div class="pageHeader">
		<div class="container">
			<h2><?=$lang->translate($title, "tool-name");?></h2>
		</div>
	</div>

	<div class="container">
		<pre><?=$sitemap;?></pre>
	</div>

<?php
	}
?>