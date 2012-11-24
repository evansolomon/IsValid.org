<?php

class Handlebars {
	static function print_templates() {
		self::print_results();
		self::print_error();
	}

	function print_results() {
	?>
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
			</div>
		</script>
	<?php
	}

	function print_error() {
	?>
		<script id="error-template" type="text/x-handlebars-template">
			<div class="row">
				<div class="span4 offset4 alert alert-error"><p>Oops, it looks like something went wrong.</p> <p>{{error.message}}</p></div>
			</div>
		</script>
	<?php
	}
}