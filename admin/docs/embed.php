<?php
	require_once "../libraries/tools.php";

	$tools = new ToolManager();
	$tools->setDatabase($db);
	$tools->init();
	//$tools->setupTools();
	//exit;

	$toolData = $tools->getToolData();

	if(!isset($_GET['id']) && !isset($_POST['id'])) {
?>

<div class="row">
	<div class="col-md-12">
		<div class="moduleBox">
			<div class="moduleBoxTitle">
				<strong>Pick a tool to embed</strong>
			</div>
			<div class="moduleBoxBody">
				<p>
					Choose a tool from the list below which you would like to embed.
				</p><br/>
				<p>
					<table width="100%">
						<tr>
						<td>
						<?php 
							$col = 0;
							$tools = -1;

							foreach($toolData as $category=>$cTools) {
						?>

						<?php
								foreach($cTools as $thetool) {
									$tools++;

									if($tools > 5) {
										echo "</td><td>";
										$tools = 0;
									}
									$toolName = $thetool[0];
									$toolIcon = $thetool[1];
									$toolDescription = urldecode($thetool[2]);
									$toolId = strtolower(str_replace(array(".", " "), "-", $toolName));
						?>
						<a href="?page=embed&id=<?php echo $toolId; ?>"><?php echo $toolName; ?></a><br/>
						<?php
								}
							}
						?>
							</td>
						</tr>
					</table>
				</p>
			</div>
		</div>
	</div>
</div>

<?php
	}
	elseif(isset($_GET['id'])) {
?>

<div class="row">
	<div class="col-md-12">
		<div class="moduleBox">
			<div class="moduleBoxTitle">
				<strong>Please Wait</strong>
			</div>
			<div class="moduleBoxBody">
				<p>
					Please wait a moment...
				</p>
				<p>
					<form action="?page=embed" method="POST" id="postForm1">
						<input type="hidden" name="id" value="<?=$_GET['id'];?>">
					</form>

					<script>
						$(document).ready(function() {
							$("#postForm1").submit();
						});
					</script>
				</p>
			</div>
		</div>
	</div>
</div>

<?php
	}
	elseif(isset($_POST['id'])) {
			$directLink = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
			$directLink = str_replace(array(
				"/admin/index.php", "/admin/", "?closeRate=1", "?clear=1", "?page=embed", "/admin/", "/admin/"
			), "", $directLink);

			$localURL = $directLink . "/tool.php?id=" . $_POST['id'] . "&embed=5";
?>

<div class="row">
	<div class="col-md-12">
		<div class="moduleBox">
			<div class="moduleBoxTitle">
				<strong>Embed Code</strong>
			</div>
			<div class="moduleBoxBody">
				<p>
					Copy and paste this HTML code and place it where you want the tool to show. It will automatically fill the width of its parent container, and you can adjust the height: ???px property in the code below.
				</p>
				<br />

				<pre>&lt;!-- Begin tool embedding --&gt;
  &lt;iframe frameborder="0" allowtransparency="1" src="<?=$localURL;?>" style="height: 500px; width: 100%;"&gt;
    Your browser is not compatable with this tool.
  &lt;/iframe&gt;
&lt;!-- End tool embedding &gt;</pre>
			</div>
		</div>
	</div>
</div>

<?php
	}
?>