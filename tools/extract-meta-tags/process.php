<?php
	$url = $application->getInput("url");
	
	$document = file_get_html($url);
	if(!$document) {
		header("Location: index.php?error=1");
		exit;
	}
	
	$description = $document->find( "meta[name=description]" );
	if(isset($description[0])) {
		$description = $description[0]->content;
	}
	else {
		$description = null;
	}
	
	
	$keywords = $document->find( "meta[name=keywords]" );
	if(isset($keywords[0])) {
		$keywords = $keywords[0]->content;
	}
	else {
		$keywords = null;
	}

	$ptitle = $document->find('title', 0);
	if($ptitle) {
		$ptitle = $ptitle->plaintext;
	}
	else {
		$ptitle = null;
	}

	//
	
	if(!$description) $description = "-";
	if(!$keywords) $keywords = "-";
	if(!$ptitle) $ptitle = "-";
?>
		<div class="pageHeader">
			<div class="container">
				<h2><?=$lang->translate($title, "tool-name");?></h2>
			</div>
		</div>

		<div class="container">
			
				<table width="100%" class="table table-striped">
					<thead>
						<tr>
							<th>
								<?=$lang->translate("Item", "thead");?>
							</th>
							<th>
								<?=$lang->translate("Content", "thead");?>
							</th>
						</tr>
					</thead>
					<tbody>
						<tr>
							<td>
								<strong><?=$lang->translate("Title", "results");?></strong>
							</td>
							<td>
								<?=$ptitle;?>
							</td>
						</tr>
						<tr>
							<td style="padding-right: 45px;">
								<strong><?=$lang->translate("Description", "results");?></strong>
							</td>
							<td>
								<?=$description;?>
							</td>
						</tr>
						<tr>
							<td>
								<strong><?=$lang->translate("Keywords", "results");?></strong>
							</td>
							<td>
								<?=$keywords;?>
							</td>
						</tr>
					</tbody>
				</table>

			<a href="<?=$path;?>./tools.php" class="btn btn-lg btn-success"><?=$lang->translate("Browse More Tools", "button");?></a>
			&nbsp;
			<a href="" class="btn btn-lg btn-danger"><?=$lang->translate("Check Another Website", "button");?></a>
		</div>