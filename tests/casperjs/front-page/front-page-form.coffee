casper.start 'http://localhost:8080'

casper.then ->
	@sendKeys '#control-samples', '1000'
	@sendKeys '#control-conversions', '200'
	@sendKeys '#experiment-samples', '2000'
	@sendKeys '#experiment-conversions', '300'

	@wait 2000, ->
		@test.assertUrlMatch /https?:\/\/[^\/]+\/\?cc=200&sc=1000&ce=300&se=2000$/, 'Form auto-submits'

casper.then ->
	permalinkClass = @evaluate ->
		$('body').hasClass 'permalink'

	@test.assertTruthy permalinkClass, 'Body has permalink class'

casper.then ->
	headerStyles = @evaluate ->
		header = $ 'h1'
		[ header.height(), header.css( 'opacity' ) ]

	@test.assertEquals headerStyles, [ 0, '0' ], 'Header is hidden'

casper.then ->
	resultCount = @evaluate ->
		$( '.result' ).length

	@test.assertEquals resultCount, 4, 'Four results loaded'

casper.run()
