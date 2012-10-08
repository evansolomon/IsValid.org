<!DOCTYPE HTML>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">

		<title>IsValid | Quantify the results of A/B tests</title>

		<!--[if lt IE 9]>
			<script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
		<![endif]-->

		<!-- Libraries -->
		<script src="js/jquery.min.js"></script>
		<script src="js/underscore.min.js"></script>
		<script src="js/handlebars.js"></script>

		<script src="js/isvalid.js"></script>

		<!-- Fonts -->
		<script type="text/javascript" src="//use.typekit.net/ivm8epx.js"></script>
		<script type="text/javascript">try{Typekit.load();}catch(e){}</script>

		<!-- Bootstrap -->
		<link href="css/bootstrap-compiled.min.css" rel="stylesheet">

		<link href="css/isvalid.css" rel="stylesheet">
		<link href="css/embed.css" rel="stylesheet">
	</head>

	<body class="embed">
		<script id="results-template" type="text/x-handlebars-template">
			<div class="row">
			{{#each results}}
				<div class="result span6">
					<h3>{{title}}</h3>
					<div class="average">{{average}}%</div>
					{{#if inputs}}
						<div class="query-input">{{inputs.conversions}} / {{inputs.samples}}</div>
						<div class="query-input"></div>
					{{/if}}
					<img src="{{chart}}" class="chart-image" alt="{{average}}">
				</div>
			{{/each}}
			</div>
			<div class="row permalink">
				<div class="span6">
					<a class="btn btn-info" href="{{permalink}}" target="_parent">Permalink</a>
				</div>
			</div>
		</script>

		<div class="container">
			<div class="results"></div>
		</div> <!-- /container -->
	</body>
</html>