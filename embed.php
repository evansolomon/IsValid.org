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
		<link href="css/embed.css" rel="stylesheet">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<link href="css/bootstrap-responsive.min.css" rel="stylesheet">
	</head>

	<body class="embed">
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
			<div class="results"></div>
		</div> <!-- /container -->
	</body>
</html>