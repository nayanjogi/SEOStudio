<?php
	$title = "Terms of Service";
	$page = 200;
	$path = "";
	
	require "structures/header.php";
?>

<div class="pageHeader">
	<div class="container">
		<h2><?=$lang->translate("Terms of Service", "title");?></h2>
	</div>
</div>

<div class="body">
	<div class="container">
		<?=str_replace("h3", "h4 style='margin: 30px 0 15px;'", file_get_contents("data/tos.html"));?>
	</div>
</div>

<?php
	require "structures/footer.php";
?>
