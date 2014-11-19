		<div class="footer">
			<div class="container">
				<div class="row">
					<div class="col-sm-4 col-md-4 col-lg-4">
						<?php
							$cp = $db->read("Copyright");
							
							date_default_timezone_set("America/Phoenix");
							
							if($db->read("CopyrightDateAuto") != "Fixed") {
								$cp = str_replace("2014", date("Y"), $cp);
								$cp = str_replace("2013", date("Y"), $cp);
								$cp = str_replace("2015", date("Y"), $cp);
								$cp = str_replace("2016", date("Y"), $cp);
							}

							echo $cp;
						?><br />
						<?=$lang->translate("All rights reserved.", "footer");?>
					</div>
					<div class="col-sm-4 col-md-4 col-lg-4">
						<div style="text-align: center;">
							<?=$lang->translate("Use of this website signifies your acceptance of our terms of service and privacy policy.", "footer");?>
						</div>
					</div>
					<div class="col-sm-4 col-md-4 col-lg-4">
						<div style="text-align: right;">
							<a href="./<?=$path;?>terms.php"><?=$lang->translate("Terms of Service", "footer");?> &raquo;</a><br />
							<a href="./<?=$path;?>privacy.php"><?=$lang->translate("Privacy Policy", "footer");?> &raquo;</a>
						</div>
					</div>
				</div>
			</div>
		</div>

		<script src="<?=$path;?>resources/bootstrap/js/bootstrap.min.js"></script>
		<script type="text/javascript">
			$(document).ready(function () {
				$("[rel=tooltip]").tooltip();
				$(".placeholder").focus(function() {
					if($(this).val() == "http://www.example.com/" || $(this).val() == "john.doe@example.com" || $(this).val() == "apples, bananas, oranges" || $(this).val() == "Apples\nOranges\nBananas" || $(this).val() == "search phrase") {
						$(this).val("");
						$(this).toggleClass("placeholder");
					}
				});
			});
		</script>

		<?php
			if($db->read("Analytics")) {
				echo $db->read("Analytics");
			}
		?>
	</body>
</html>