casper.start 'http://localhost:8080'

content = ''

casper.then ->
	@sendKeys '#control-samples', '1000'
	@sendKeys '#control-conversions', '200'
	@sendKeys '#experiment-samples', '2000'
	@sendKeys '#experiment-conversions', '300'

	@wait 2000, ->
		@test.assertUrlMatch /https?:\/\/[^\/]+\/\?cc=200&sc=1000&ce=300&se=2000&significance=0.9$/, 'Form auto-submits'
		content = @getHTML '.results'

casper.then ->
	@sendKeys '#control-samples', '1'

	@wait 2000, ->
		@test.assertUrlMatch /https?:\/\/[^\/]+\/\?cc=200&sc=10001&ce=300&se=2000&significance=0.9$/, 'Push state on nav'

casper.back()

casper.then ->
	@wait 2000, ->
		@test.assertEquals @getHTML( '.results' ), content, 'Back nav'

casper.run()
