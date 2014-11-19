
	<div class="pageHeader">
		<div class="container">
			<h2><?=$lang->translate($title, "tool-name");?></h2>
		</div>
	</div>

	<div class="container">
		<form action="" method="POST">
			<input type="hidden" name="doFormPostback" value="<?=md5($title);?>">

			<div class="row">
				<div class="col-md-6">
					<h4 style="margin: 0 0 15px 0;"><?=$lang->translate("Describe Your Website", "thead");?></h4>
					
					<textarea name="description" class="form-control input-block" rows="2"></textarea>
				</div>
				<div class="col-md-6">
					<h4 style="margin: 0 0 15px 0;"><?=$lang->translate("List Your Keywords", "thead");?></h4>
					
					<textarea name="keywords" class="form-control input-block placeholder" rows="2">apples, bananas, oranges</textarea>
				</div>
			</div>

			<div style="height: 30px;"></div>

			<div class="row">
				<div class="col-md-6">
					<h4 style="margin: 0 0 15px 0;"><?=$lang->translate("Allow robots to index your website?", "thead");?></h4>
					
					<select name="robots_index" class="form-control input-block">
						<option value="index"><?=$lang->translate("Yes");?></option>
						<option value="noindex"><?=$lang->translate("No");?></option>
					</select>
				</div>
				<div class="col-md-6">
					<h4 style="margin: 0 0 15px 0;"><?=$lang->translate("Allow robots to crawl your website?", "thead");?></h4>
					
					<select name="robots_follow" class="form-control input-block">
						<option value="follow"><?=$lang->translate("Yes");?></option>
						<option value="nofollow"><?=$lang->translate("No");?></option>
					</select>
				</div>
			</div>

			<div style="height: 30px;"></div>

			<div class="row">
				<div class="col-md-6">
					<h4 style="margin: 0 0 15px 0;"><?=$lang->translate("What type of content will your site display?", "thead");?></h4>
					
					<select name="content_type" class="form-control input-block">
						<option value="text/html; charset=utf-8">UTF-8 (<?=$lang->translate("recommended");?>)</option>
						<option value="text/html; charset=iso-8859-1">ISO-8859-1</option>
					</select>
				</div>
				<div class="col-md-6">
					<h4 style="margin: 0 0 15px 0;"><?=$lang->translate("What is your site's primary language?", "thead");?></h4>
					
					<select name="language" class="form-control input-block">
						<option value="English">English</option>
						<option value="Spanish">Spanish</option>
						<option value="Mandarin">Mandarin</option>
						<option value="Japanese">Japanese</option>
						<option value="Korean">Korean</option>
						<option value="French">French</option>
						<option value="Portuguese">Portuguese</option>
						<option value="Malay-Indonesian">Malay-Indonesian</option>
						<option value="Bengali">Bengali</option>
						<option value="Arabic">Arabic</option>
						<option value="Russian">Russian</option>
						<option value="Hindustani">Hindustani</option>
						<option value="N/A"><?=$lang->translate("Other");?></option>
					</select>
				</div>
			</div>

			<hr />
			<button type="submit" class="btn btn-success btn-lg"><?=$lang->translate("Continue", "button");?></button>
		</form>
	</div>