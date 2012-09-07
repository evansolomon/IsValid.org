$.support.history = !! ( window.history && history.pushState );

// The most important function.
// Con Con never has bugs.
var conconjr;
(function(){
	var canvas, context, clear, lines = [];

	requestAnimFrame = (function(){
		return  window.requestAnimationFrame       ||
				window.webkitRequestAnimationFrame ||
				window.mozRequestAnimationFrame    ||
				window.oRequestAnimationFrame      ||
				window.msRequestAnimationFrame     ||
				function(/* function */ callback, /* DOMElement */ element){
					window.setTimeout(callback, 1000 / 60);
				};
	})();

	conconjr = function() {
		if ( ! $('.header').is(':visible') )
			return;

		init();
		animate();
	};

	function init() {
		var $conconjr = $('#conconjr'),
		context = $conconjr[0].getContext('2d');
		context.fillStyle = '#dfdfdf';

		clear = function() {
			context.clearRect( 0, 0, $conconjr.width(), $conconjr.height() );
		};

		// Transform the coordinates.
		context.translate( 0, $conconjr.height() );
		context.scale( 1, -1 );

		// Create the lines.
		var peak  = 30, // height
			count = 50, // number of lines
			index = count;

		while ( index-- ) {
			var mean = count / 2;
			var diff = Math.abs( mean - index );
			var maxY = Math.round( Math.pow( Math.abs( mean - diff ), 2 ) * ( peak / Math.pow( mean, 2 ) ) );

			lines.push( new Line({
				height:  0,
				x:       index * 5,
				context: context,
				maxY:    maxY
			}) );
		}
	}

	function animate() {
	    requestAnimFrame( animate );
	    draw();
	}

	function easeInOutQuad( time, start, change, steps ) {
		time /= steps / 2;
		if ( time < 1 )
			return change / 2 * time * time + start;
		time--;
		return -change / 2 * ( time * ( time - 2 ) - 1 ) + start;
	}

	function randomBetween( min, max ) {
		return Math.round( ( Math.random() * ( max - min ) ) + min );
	}

	var Line = function( options ) {
		_.defaults( options || {}, {
			x:        0,
			y:        0,
			width:    3,
			height:   0,
			ease:     easeInOutQuad,
			minY:     0,
			maxY:     30,
			minSteps: 15,
			maxSteps: 45
		});

		_.extend( this, options );
		this.reset();
	};

	_.extend( Line.prototype, {
		change: function() {
			return this.end - this.start;
		},

		reset: function() {
			this.time  = 1;
			this.start = this.height;
			this.end   = randomBetween( this.minY, this.maxY );
			this.steps = randomBetween( this.minSteps, this.maxSteps );
		},

		draw: function() {
			this.time++;
			this.height = Math.round( this.ease( this.time, this.start, this.change(), this.steps ) );

			this.context.fillRect( this.x, this.y, this.width, this.height );

			if ( this.height === this.end )
				this.reset();
		}
	});

	function draw() {
		clear();
		_.invoke( lines, 'draw' );
	}

	conconjr.draw = draw;
}());

function roundNumber( number, decimals ) {
	decimals = decimals || 0;
	return Math.round( number * Math.pow( 10, decimals ) ) / Math.pow( 10, decimals );
}

function getPermalinkQuery( query ) {
	var results = {},
		conConMap = {
			conversions_control:    'cc', // con con
			samples_control:        'sc', // con sam
			conversions_experiment: 'ce', // test con
			samples_experiment:     'se'  // test sam
		};

	$.map( query, function( value, key ) {
		results[ conConMap[ key ] ] = value;
	});

	return results;
}

function isPermalinkPage() {
	if(! getParameter("cc"))
		return false;
	if(! getParameter("sc"))
		return false;
	if(! getParameter("ce"))
		return false;
	if(! getParameter("se"))
		return false;

	return true;
}

function renderResults( stat_results, query ) {
	var results = [],
		percentagize,
		permalink;

	percentagize = function( numbers ) {
		var percents = {};
		$.each( numbers, function( key, value ) {
			percents[ key ] = roundNumber( 100 * value, 1 );
		});
		return percents;
	};

	// Control
	results.push( $.extend({
		title: 'Original',
		chart: stat_results.confidence.chart.control
	}, percentagize( stat_results.confidence.results.control ) ) );

	// Experiment
	results.push( $.extend({
		title: 'Experiment',
		chart: stat_results.confidence.chart.experiment
	}, percentagize( stat_results.confidence.results.experiment ) ) );

	// Significance
	results.push( $.extend({
		title: 'Significance',
		chart: stat_results.significance.chart
	}, percentagize({ average: stat_results.significance.results.experiment }) ) );

	// Improvement
	results.push( $.extend({
		title: 'Improvement',
		chart: stat_results.improvement.chart
	}, percentagize( stat_results.improvement.results ) ) );

	var source   = $('#results-template').html();
	var template = Handlebars.compile(source);
	var html     = template({ results: results });
	$('.results').html(html);

}

function getResults( query ) {
	// Don't run the same query twice in a row
	if ( '?' + $.param( query ) === window.location.search )
		return false;

	return queryAPI( query ).done(function(stat_results) {
		// Check for errors
		if(stat_results.error)
			return false;

		// Change the URL
		var search = '?' + $.param( getPermalinkQuery( query ) );
		if ( $.support.history && search !== window.location.search && $('.header').is(':visible') )
			history.pushState( query, '', search );

		$('.results').empty();
		$('body').removeClass('home').addClass('permalink');
		renderResults( stat_results, query );
	});
}

function syncFormWithPermalink() {
	$("input#control-conversions").val( getParameter("cc") );
	$("input#control-samples").val( getParameter("sc") );
	$("input#experiment-conversions").val( getParameter("ce") );
	$("input#experiment-samples").val( getParameter("se") );
}

function queryAPI( query ) {
	var queryString = $.param( query );
	return $.getJSON("api?" + queryString);
}

function getParameter(paramName) {
	var searchString = window.location.search.substring(1),
		i, val, params = searchString.split("&");

	for (i=0;i<params.length;i++) {
		val = params[i].split("=");

		if (val[0] == paramName)
			return unescape(val[1]);
	}
	return null;
}

function isFormComplete() {
	if(! $("input#control-conversions").val())
		return false;
	if(! $("input#control-samples").val())
		return false;
	if(! $("input#experiment-conversions").val())
		return false;
	if(! $("input#experiment-samples").val())
		return false;

	return true;
}

$(function() {
	// Bind popstate listener
	if ( $.support.history ) {
		$(window).on( 'popstate', function( event ) {
			var state = event.originalEvent.state;
			if ( state ) {
				getResults( state );
				syncFormWithPermalink();
			}
		});
	}

	// Focus on the first input
	$("form :input:visible:first").focus();

	// Listen for form submit
	$("form").on('submit', function(event){
		event.preventDefault();

		// Don't submit incomplete forms
		if ( ! isFormComplete() )
			return false;

		getResults({
			conversions_control:    $("input#control-conversions").val(),    // con con
			samples_control:        $("input#control-samples").val(),        // con sam
			conversions_experiment: $("input#experiment-conversions").val(), // test con
			samples_experiment:     $("input#experiment-samples").val()      // test sam
		});
	});

	// Auto-load results on permalink pages
	if ( isPermalinkPage() ) {
		syncFormWithPermalink();

		getResults({
			conversions_control:    getParameter("cc"), // con con
			samples_control:        getParameter("sc"), // con sam
			conversions_experiment: getParameter("ce"), // test con
			samples_experiment:     getParameter("se")  // test sam
		});
	}
	else {
		$('body').removeClass('permalink').addClass('home');
	}

	// Auto-submit the form
	var keyup_timer;
	$("form input[type=text]").keyup(function(){
		clearTimeout(keyup_timer);
		keyup_timer = setTimeout(function(){
			$('form').submit();
		}, 800);
	});

	conconjr();
});
