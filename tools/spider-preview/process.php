<?php
	$url = $application->getInput("url");

	$html = file_get_html( $url );
	if(!$html) {
		doFault();
	}
	
	$www = parse_url($url);
	$shortWebsite = $www['scheme'] . "://" . $www['host'] . "/";
?>
		<div class="pageHeader">
			<div class="container">
				<h2><?=$lang->translate($title, "tool-name");?></h2>
			</div>
		</div>

		<div class="container">
			<table class="table table-bordered table-striped" width='100%' cellspacing='0px' cellpadding='0px'>
				<thead>
					<tr>
						<th colspan='2' style="font-weight: bold;"><b><?=$lang->translate("Webpage Configuration", "thead");?></b></th>
					</tr>
				</thead>
				<tbody><tr>
					<td class="Col Name" width='125'><?=$lang->translate("Page Address", "thead");?></td>
					<td class="Col Content">
						<?php
							echo $url;
						?>
					</td>
				</tr>
				<tr>
					<td class="Col Name"><?=$lang->translate("Page Title", "thead");?></td>
					<td class="Col Content">
						<?php
							$title = $html->find('title', 0);
							if(isset($title))
								echo $title->innertext;
							else
								echo "?";
						?>
					</td>
				</tr>
				<tr>
					<td class="Col Name"><?=$lang->translate("Page Description", "thead");?></td>
					<td class="Col Content">
						<?php
							$title = $html->find('meta[name=description]', 0);
							if(isset($title))
								echo $title->content;
							else
								echo "?";
						?>
					</td>
				</tr>
				<tr>
					<td class="Col Name"><?=$lang->translate("Page Keywords", "thead");?></td>
					<td class="Col Content">
						<?php
							$title = $html->find('meta[name=keywords]', 0);
							if(isset($title))
								echo $title->content;
							else
								echo "?";
						?>
					</td>
				</tr></tbody>
				<thead><tr class="HeadRow">
					<td colspan='2' style="font-weight: bold;"><?=$lang->translate("Website Contents", "thead");?></td>
				</tr></thead>
				<tbody><tr>
					<td class="Col Name">Document Size</td>
					<td class="Col Content">
						<?php
							$text = $html->innertext;
							echo strlen($text);
						?>
					</td>
				</tr>
				<tr>
					<td class="Col Name"><?=$lang->translate("Document Text", "thead");?></td>
					<td class="Col Content">
						<?php
							$text = $html->innertext;
							$text = preg_replace('/<script\b[^>]*>(.*?)<\/script>/is', "", $text);
							$words = preg_split("/[\s,]+/", strip_tags($text));
							
							echo implode(" ", $words);
						?>
					</td>
				</tr>
				<tr>
					<td class="Col Name"><?=$lang->translate("Number of Words", "thead");?></td>
					<td class="Col Content">
						<?php
							$text = $html->innertext;
							$text = preg_replace('/<script\b[^>]*>(.*?)<\/script>/is', "", $text);
							$words = preg_split("/[\s,]+/", strip_tags($text));
							
							echo count($words);
						?>
					</td>
				</tr></tbody>
				<thead><tr class="HeadRow">
					<td colspan='2' style="font-weight: bold;"><?=$lang->translate("Search Engine Status", "thead");?></td>
				</tr></thead>
				<tbody><tr>
					<td class="Col Name">Indexed Pages</td>
					<td class="Col Content">
						<a href="http://google.com/search?q=site:<?=$shortWebsite;?>">Google</a>
						&nbsp;
						<a href="http://siteexplorer.search.yahoo.com/search?p=<?=$shortWebsite;?>">Yahoo</a>
						&nbsp;
						<a href="http://search.live.com/results.aspx?q=<?=$shortWebsite;?>">Bing</a>
						&nbsp;
						<a href="http://www.ask.com/web?q=inurl:<?=$shortWebsite;?>+site:<?=$shortWebsite;?>">Ask</a>
					</td>
				</tr>
				<tr>
					<td class="Col Name"><?=$lang->translate("Web Cache", "thead");?></td>
					<td class="Col Content">
						<a href="http://google.com/search?q=cache:<?=$shortWebsite;?>">Google</a>
					</td>
				</tr>
				<tr>
					<td class="Col Name"><?=$lang->translate("Web Mentions", "thead");?></td>
					<td class="Col Content">
						<a href="https://www.google.com/search?q=%22<?=$shortWebsite;?>%22">Google</a>
					</td>
				</tr></tbody>
				<thead><tr class="HeadRow">
					<td colspan='2' style="font-weight: bold;"><?=$lang->translate("Miscellaneous", "thead");?></td>
				</tr></thead>
				<tbody><tr>
					<td class="Col Name"><?=$lang->translate("Website Source", "thead");?></td>
					<td class="Col Content">
						<div style="width: 600px; background: #ddd; border: 1px solid #999; padding: 10px; overflow-y: auto; max-height: 150px;">
							<?=str_replace("<", "&lt;", str_replace(">", "&gt;", str_replace("\n", "<br />", str_replace("	", "&nbsp;&nbsp;&nbsp;", str_replace(" ", "&nbsp;", $html)))));?>
						</div>
					</td>
				</tr></tbody>
			</table>

			<hr />

			<a href="<?=$path;?>./tools.php" class="btn btn-lg btn-success"><?=$lang->translate("Browse More Tools", "button");?></a>
			&nbsp;
			<a href="" class="btn btn-lg btn-danger"><?=$lang->translate("Check Another Website", "button");?></a>
		</div>