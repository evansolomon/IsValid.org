<!DOCTYPE HTML>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<title>IsValid | Quantify the results of A/B tests</title>

		<!--[if lt IE 9]>
			<script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
		<![endif]-->
		<script src="js/jquery-1.8.1.min.js"></script>
		<script src="js/underscore.min.js"></script>
		<script src="js/isvalid.js"></script>
		<script src="js/handlebars.js"></script>
		<script type="text/javascript" src="//use.typekit.net/ivm8epx.js"></script>
		<script type="text/javascript">try{Typekit.load();}catch(e){}</script>

		<link href="css/bootstrap.min.css" rel="stylesheet">
		<link href="css/isvalid.css" rel="stylesheet">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<link href="css/bootstrap-responsive.min.css" rel="stylesheet">
	</head>

	<body class="permalink">
		<script id="results-template" type="text/x-handlebars-template">
			<div class="row">
			{{#each results}}
				<div class="result span6">
					<h3>{{title}}</h3>
					<div class="average">{{average}}%</div>
					{{#if high}}
						<div class="range">{{low}} – {{high}}</div>
					{{/if}}
					<img src="{{chart}}" class="chart-image" alt="{{average}}">
				</div>
			{{/each}}
		</script>

		<div class="container">
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
						<input class="input-xlarge" type="text" id="control-samples" placeholder="Original">
					</div>
					<div class="control-group">
						<input class="input-xlarge" type="text" id="experiment-samples" placeholder="Experiment">
					</div>
				</div>
				<div class="span6">
					<h2>Conversions</h2>
					<div class="control-group">
						<input class="input-xlarge" type="text" id="control-conversions" placeholder="Original">
					</div>
					<div class="control-group">
						<input class="input-xlarge" type="text" id="experiment-conversions" placeholder="Experiment">
					</div>
				</div>
			</div>
			</form>
			<div class="row"><p class="home-tip">Results will automatically load when you fill in each item</p></div>
			<div class="results"></div>
		</div> <!-- /container -->
		<div class="row footer">
			<a href="https://github.com/evansolomon/IsValid.org">Source code</a> and <a href="https://github.com/evansolomon/IsValid.org/wiki/API">API documentation</a> on Github
		</div>

	</body>
</html>