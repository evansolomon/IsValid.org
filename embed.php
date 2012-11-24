<?php print_header( true ); ?>
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