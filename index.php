<?php require_once( dirname( __FILE__ ) . '/isvalid-load.php' ); ?>
<?php print_header(); ?>
	<body class="permalink">
		<div class="container main">
			<!-- Header -->
			<div class="header">
				<h1>IsValid</h1>
				<canvas id="conconjr" width="250" height="30">CON CON NEVER HAS BUGS. IT IS YOUR BROWSER THAT HAS BUGS.</canvas>
				<hr />
				<p>Quantify experiment results</p>
			</div>

			<form class="horizontal-form">
			<div class="row">
				<div class="span6">
					<h2>Samples</h2>
					<div class="control-group">
						<input class="input-xlarge" type="text" id="control-samples" tabindex="1" placeholder="Original">
					</div>
					<div class="control-group">
						<input class="input-xlarge" type="text" id="experiment-samples" tabindex="3" placeholder="Experiment">
					</div>
				</div>
				<div class="span6">
					<h2>Conversions</h2>
					<div class="control-group">
						<input class="input-xlarge" type="text" id="control-conversions" tabindex="2" placeholder="Original">
					</div>
					<div class="control-group">
						<input class="input-xlarge" type="text" id="experiment-conversions" tabindex="4" placeholder="Experiment">
					</div>
				</div>
			</div>
			</form>
			<div class="row">
				<div class="span4 offset4 alert alert-info" style="display:none;">Results will automatically load when you fill in each field</div>
			</div>
			<div class="results"></div>
		</div> <!-- /container -->
		<div class="container">
			<div class="row footer">
				<?php print_footer(); ?>
			</div>
		</div>

	</body>
</html>