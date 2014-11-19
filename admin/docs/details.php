<div class="row">
	<div class="col-md-8">
		<div class="moduleBox">
			<div class="moduleBoxTitle">
				<strong>Installation Details</strong>
			</div>
			<div class="moduleBoxBody">
				<table class="table table-striped">
					<thead>
						<tr>
							<th>Item</th>
							<th>Detail</th>
						</tr>
					</thead>
					<tbody>
						<tr>
							<th width="200px">Version</th>
							<td><?=$db->read("Version");?></td>
						</tr>
						<tr>
							<th width="200px">Path</th>
							<td><?=str_replace(array("\admin\docs\details.php", "/admin/docs/details.php"), "", __FILE__);?></td>
						</tr>
						<tr>
							<th width="200px">Admin Email</th>
							<td><?=$db->read("Admin Email");?></td>
						</tr>
					</tbody>
				</table>
			</div>
		</div>
		<div class="moduleBox">
			<div class="moduleBoxTitle">
				<strong>Utilities</strong>
			</div>
			<div class="moduleBoxBody">
				<div class="row">
					<div class="col-md-4">
						<div style="text-align: center; font-size: 18px; padding: 30px 0;">
							<span class="glyphicon glyphicon-dashboard" style="font-size: 64px;"></span>
							<a href="?page=diagnostics" style="display: block; margin-top: 10px;">Diagnostics</a>
						</div>
					</div>
					<div class="col-md-4">
						<div style="text-align: center; font-size: 18px; padding: 30px 0;">
							<span class="glyphicon glyphicon-globe" style="font-size: 64px;"></span>
							<a href="?page=updates" style="display: block; margin-top: 10px;">New Updates</a>
						</div>
					</div>
					<div class="col-md-4">
						<div style="text-align: center; font-size: 18px; padding: 30px 0;">
							<span class="glyphicon glyphicon-question-sign" style="font-size: 64px;"></span>
							<a href="http://codecanyon.net/user/webfector" style="display: block; margin-top: 10px;">Support</a>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="col-md-4">
		<div class="moduleBox">
			<div class="moduleBoxTitle">
				<strong>Application Token</strong>
			</div>
			<div class="moduleBoxBody">
				<?=$db->read("Token");?>
			</div>
		</div>
		<div class="moduleBox">
			<div class="moduleBoxTitle">
				<strong>Current API Channels</strong>
			</div>
			<div class="moduleBoxBody">
				api.webfector.com<br />
				api.altusia.com
			</div>
		</div>
		<div class="moduleBox">
			<div class="moduleBoxTitle">
				<strong>Automatic Updates</strong>
			</div>
			<div class="moduleBoxBody">
				Disabled in this version.
			</div>
		</div>
		<div class="moduleBox">
			<div class="moduleBoxTitle">
				<strong>Customer Support</strong>
			</div>
			<div class="moduleBoxBody">
				Email me from <a href="http://codecanyon.net/user/webfector">my profile page</a> for support.
			</div>
		</div>
	</div>
</div>