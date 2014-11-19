<?php
	$url = $application->getInput("url");

	$curl = curl_init();
	curl_setopt($curl, CURLOPT_URL, $url);
	curl_setopt($curl, CURLOPT_HEADER, true);
	curl_setopt($curl, CURLOPT_FILETIME, true);
	curl_setopt($curl, CURLOPT_NOBODY, true);
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
	$header = curl_exec_follow($curl);
	curl_close($curl);
	
	$header = str_replace("\r\n\r\n", "\r\n\r\n"."-------------------> v"."\r\n\r\n", $header);

	if($header && strpos($header, "SAMEORIGIN") !== false) {
		$cannotLoad = true;
	}
?>

	<div class="pageHeader">
		<div class="container">
			<h2><?=$lang->translate($title, "tool-name");?></h2>
		</div>
	</div>

	<script>
		var initDraw = false;

		function redraw() {

			$(".loadingRender").fadeIn('fast');

			var size = $("#canvasSize").val();
			sizes = size.split("x");

			console.log("Canvas WIDTH set to " + sizes[0] + " pixels.");
			console.log("Canvas HEIGHT set to " + sizes[1] + " pixels.");

			$("#webPreviewRender").attr("height", sizes[1] + 'px');
			$("#webPreviewRender").attr("width", sizes[0] + 'px');

			$("#webPreviewRenderSrc").attr("height", sizes[1] + 'px');
			$("#webPreviewRenderSrc").attr("width", sizes[0] + 'px');

			$("#webPreviewRenderSrc").attr("src", "<?=$url;?>");
			initDraw = true;
		}
		function frameLoaded() {
			if(!initDraw) {
				redraw();
			}
			else {
				$(".loadingRender").fadeOut();
			}
		}
	</script>

	<div class="container">
		<form>
			<div class="input-group">
				<select name="size" id="canvasSize" class="input-lg form-control">
					<option value="1212x868">Bootstrap Desktop (1200px)</option>
					<option value="995x868">Bootstrap Md Desktop (992px)</option>
					<option value="768x868">Bootstrap Tablet (768px)</option>
					<option value="600x868">Bootstrap Phone (below 768px)</option>
					<option value="-">&nbsp;</option>
					<option value="480x320">Apple iPhone 4/4S</option>
					<option value="1136x640">Apple iPhone 5</option>
					<option value="800x400">Sony Erricson Xperia</option>
					<option value="400x240">LG + Samsung</option>
					<option value="720x1280">HTC One X / One X+</option>
					<option value="480x800">Desire / Nexus</option>
					<option value="1920x1080">HTC One</option>
					<option value="720x1280">Xperia S</option>
					<option value="480x854">Xperia Sola</option>
					<option value="854x480">Xperia U</option>
					<option value="720x1280">Xperia Ion</option>
					<option value="1920x1080">Xperia Z</option>
					<option value="1920x1080">Microsoft Surface Pro</option>
					<option value="1366x768">Microsoft Surface</option>
				</select>
				<span class="input-group-btn">
					<button class="btn btn-primary btn-lg" type="button" onclick="redraw();">Redraw</button>
				</span>
			</div>
		</form>

		<hr />

		<div id="webPreviewCanvas">
			<div class="loadingRender">
				<img src="<?=$path;?>./resources/images/loading1.gif" style="position: absolute; top: 22px; left: 22px;">
				<br /><br /><br />
				&nbsp; &nbsp;Loading...
			</div>
			<div id="webPreviewRender">
				<iframe src="" id="webPreviewRenderSrc" frameBorder="0" onload="frameLoaded();"></iframe>
			</div>
		</div>

		<hr />

		<a href="<?=$path;?>./tools.php" class="btn btn-lg btn-success"><?=$lang->translate("Browse More Tools", "button");?></a>
		&nbsp;
		<a href="" class="btn btn-lg btn-danger"><?=$lang->translate("Check Another Website", "button");?></a>
	</div>

	<?php if(isset($cannotLoad)) {
	?>
	<script>
		alert("Failed to draw canvas for '<?=$url;?>'. This website does not allow external websites to embed it.");
	</script>
	<?php
	}
	?>

	<style>
		#webPreviewCanvas {
			background: #FFF;
			border: 1px solid #bbb;
			overflow: auto;
			max-height: 800px;
			position: relative;
		}
		.loadingRender {
			background: #EEE;
			position: absolute;
			width: 100%;
			height: 100%;
			top: 0;
			left: 0;
		}
	</style>