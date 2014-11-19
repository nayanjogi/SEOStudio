
<div style="padding-left: 20px;">
	<h3>Shop Addons</h3>
	<hr />

	<?php
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, "http://api.webfector.com/public/addons/seo-studio.php");
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 15);
		curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.13) Gecko/20080311 Firefox/2.0.0.13');
		$data = curl_exec($ch);
		curl_close($ch);

		if(!$data || strpos($data, "CONFIRM_OK") === false) {
			echo "<strong>Error!</strong> Could not download addon list. This might be a temporary problem. Wait a few minutes and try again, or <a href='http://www.webfector.com/support/status/'>click here to see the status of our services</a>.";
		}
		else {
			echo $data;
		}
	?>
</div>