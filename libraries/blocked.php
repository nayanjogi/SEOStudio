<?php
	$title = "Human Verification";
	$page = 2;
	$path = "../";
	
	require $path . "structures/header.php";

    
?>
<div class="arrow_box">
	<div class="container">
		<h3>Human Verification</h3>
	</div>
</div>

<div class="body">
	<div class="container">
		<?php if(isset($_GET['fail'])) { ?>
			<p>
				<div class="alert alert-error">
					<button type="button" class="close" data-dismiss="alert">&times;</button>
					
					<strong>Error!</strong> Code entered did not match the image provided. Please try again.
				</div>
			</p>
		<?php } ?>
		
		<p>Before continuing, you must enter a Captcha Code. This website does not tolerate robots or automated macros.</p>
		
		<p class="iForm">
			<form method="post" action="blocked.php?captcha=1">
				<?php
				  echo recaptcha_get_html($publickey);
				?>
				<input type="submit" />
			</form>
		</p>
	</div>
</div>

<?php
	require $path . "structures/footer.php";
?>
