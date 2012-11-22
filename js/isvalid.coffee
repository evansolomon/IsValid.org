$.support.history = !! ( window.history && history.pushState )

Number.prototype.approximate = ->
	return @ if @ < 1000

	thousands = Math.round( @ / 100 ) / 10
	return thousands.toString() + 'K' if thousands < 1000

	millions = Math.round( thousands / 100 ) / 10
	millions.toString() + 'M'

( ->
	lines = []

	@requestAnimFrame = ( ->
		window.requestAnimationFrame       ||
		window.webkitRequestAnimationFrame ||
		window.mozRequestAnimationFrame    ||
		window.oRequestAnimationFrame      ||
		window.msRequestAnimationFrame     ||
		( callback, element ) ->
			window.setTimeout callback, 1000 / 60
	)()

	@conconjr = ->
		return unless $('.header').is ':visible'
		init()
		animate()

	init = ->
		$conconjr = $ '#conconjr'
		context = $conconjr[0].getContext '2d'
		context.fillStyle = '#dfdfdf'

		@clear = ->
			context.clearRect 0, 0, $conconjr.width(), $conconjr.height()

		context.translate 0, $conconjr.height()
		context.scale 1, -1

		peak  = 30
		count = index = 50

		while index--
			mean = count / 2
			diff = Math.abs mean - index
			maxY = Math.round Math.pow( Math.abs( mean - diff ), 2 ) * ( peak / Math.pow( mean, 2 ) )

			lines.push new Line
				height:  0
				x:       index * 5
				context: context
				maxY:    maxY

	animate = ->
		requestAnimFrame animate
		draw()

	easeInOutQuad = ( time, start, change, steps ) ->
		time /= steps / 2
		if time < 1
			return change / 2 * time * time + start

		time--
		-change / 2 * ( time * ( time - 2 ) - 1 ) + start

	randomBetween = ( min, max ) ->
		Math.round ( Math.random() * ( max - min ) ) + min

	Line = ( options = {} ) ->
		_.defaults options,
			x:        0
			y:        0
			width:    3
			height:   0
			ease:     easeInOutQuad
			minY:     0
			maxY:     30
			minSteps: 15
			maxSteps: 45

		_.extend @, options
		@.reset()

	_.extend Line::,
		change: ->
			@end - @start

		reset: ->
			@time  = 1
			@start = @height
			@end   = randomBetween @minY, @maxY
			@steps = randomBetween @minSteps, @maxSteps

		draw: ->
			@time++
			@height = Math.round @ease( @time, @start, @change(), @steps )
			@context.fillRect @x, @y, @width, @height

			@reset() if @height is @end

	draw = ->
		@clear()
		_.invoke lines, 'draw'

	@conconjr.draw = draw
)()


roundNumber = ( number, decimals = 0 ) ->
	Math.round( number * Math.pow( 10, decimals ) ) / Math.pow( 10, decimals )

getPermalinkQuery = ( query ) ->
	results = {}
	conConMap =
		conversions_control:    'cc', # con con
		samples_control:        'sc', # con sam
		conversions_experiment: 'ce', # test con
		samples_experiment:     'se'  # test sam

	$.map query, ( value, key ) ->
		results[ conConMap[ key ] ] = value

	results

isPermalinkPage = ->
	return false unless getParameter 'cc'
	return false unless getParameter 'sc'
	return false unless getParameter 'ce'
	return false unless getParameter 'se'

	true

getTemplateOutput = ( template, input ) ->
	source   = template.html()
	compiled = Handlebars.compile source

	compiled input

renderError = ( error ) ->
	html = getTemplateOutput $('#error-template'), { error }
	printResult html

renderResults = ( stat_results, query ) ->
	results = []

	percentagize = ( numbers ) ->
		percents = {}

		$.each numbers, ( key, value ) ->
			value = 100 * value

			# Percents over 1000 don't need decimals
			decimals = if ( value >= 1000 ) then 0 else 1
			percents[ key ] = roundNumber value, decimals

		percents

	permalink = "http://#{window.location.host}?" + $.param( getPermalinkQuery query )

	# Control
	results.push $.extend
		title: 'Original'
		chart: stat_results.confidence.chart.control
		inputs:
			conversions: parseInt( query.conversions_control, 10 ).approximate()
			samples: parseInt( query.samples_control, 10 ).approximate()
	, percentagize stat_results.confidence.results.control

	 # Experiment
	results.push $.extend
		title: 'Experiment'
		chart: stat_results.confidence.chart.experiment
		inputs:
			conversions: parseInt( query.conversions_experiment, 10 ).approximate()
			samples: parseInt( query.samples_experiment, 10 ).approximate()
	, percentagize stat_results.confidence.results.experiment

	# Significance
	results.push $.extend
		title: 'Significance'
		chart: stat_results.significance.chart
	, percentagize { average: stat_results.significance.results.experiment }

	# Improvement
	results.push $.extend
		title: 'Improvement'
		chart: stat_results.improvement.chart
	, percentagize stat_results.improvement.results

	html = getTemplateOutput $('#results-template'), { results, permalink }
	printResult html

printResult = ( html, options = {} ) ->
	$('.results').fadeOut 200, ->
		$(this).hide().delay( 300 ).html( html ).fadeIn options.speed

getResults = ( query, options = {} ) ->
	lastQuery = window.location.search
	newQuery  = '?' + $.param( getPermalinkQuery query )

	# Change the URL
	if $.support.history && newQuery != window.location.search && $('.header').is ':visible'
		history.pushState query, '', newQuery

	# Don't run the same query twice in a row
	return false if not options.force && lastQuery is newQuery

	return queryAPI( query ).done ( stat_results ) ->
		$('.alert').fadeOut()

		# Check for errors
		return renderError stat_results.error if stat_results.error

		$('body').removeClass( 'home' ).addClass 'permalink'
		renderResults stat_results, query

syncFormWithPermalink = ->
	$("input#control-conversions").val getParameter( 'cc' )
	$("input#control-samples").val getParameter( 'sc' )
	$("input#experiment-conversions").val getParameter( 'ce' )
	$("input#experiment-samples").val getParameter( 'se' )

queryAPI = ( query ) ->
	$.getJSON 'api?' + $.param query

getParameter = ( paramName ) ->
	searchString = window.location.search.substring 1
	params = searchString.split '&'

	for param in params
		val = param.split( '=' )
		return unescape val[ 1 ] if val[ 0 ] is paramName

	null

isFormComplete = ->
	return false unless $('input#control-conversions').val()
	return false unless $('input#control-samples').val()
	return false unless $('input#experiment-conversions').val()
	return false unless $('input#experiment-samples').val()

	true

$ ->
	# Bind popstate listener
	if $.support.history
		$(window).on 'popstate', ( event ) ->
			state = event.originalEvent.state
			if state
				getResults state, { force: true }
				syncFormWithPermalink()

	# Focus on the first input
	$("form :input:visible:first").focus()

	# Listen for form submit
	$("form").on 'submit', ( event ) ->
		event.preventDefault()

		# Don't submit incomplete forms
		return false unless isFormComplete()

		getResults
			conversions_control:    $('input#control-conversions').val()    # con con
			samples_control:        $('input#control-samples').val()        # con sam
			conversions_experiment: $('input#experiment-conversions').val() # test con
			samples_experiment:     $('input#experiment-samples').val()     # test sam

	# Auto-load results on permalink pages
	if isPermalinkPage()
		syncFormWithPermalink()

		getResults
			conversions_control:    getParameter( 'cc' ) # con con
			samples_control:        getParameter( 'sc' ) # con sam
			conversions_experiment: getParameter( 'ce' ) # test con
			samples_experiment:     getParameter( 'se' ) # test sam
		, { force: true }

	else
		$('body').removeClass( 'permalink' ).addClass 'home'
		$('.alert-info').delay( 1400 ).fadeIn 'slow'

	# Auto-submit the form
	$( 'form input[type=text]' ).keyup ->
		clearTimeout @keyup_timer
		@keyup_timer = setTimeout ->
			$('form').submit()
		, 800

	conconjr()