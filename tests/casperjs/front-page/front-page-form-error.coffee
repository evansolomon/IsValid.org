casper.start 'http://localhost:8080'

casper.then ->
	@sendKeys '#control-samples', '1'
	@sendKeys '#control-conversions', '200'
	@sendKeys '#experiment-samples', '1'
	@sendKeys '#experiment-conversions', '300'

	@wait 2000, ->
		@test.assertUrlMatch /https?:\/\/[^\/]+\/\?cc=200&sc=1&ce=300&se=1&significance=0.9$/, 'Form auto-submits'

casper.then ->
	@test.assertVisible '.alert-error', 'Error message loads'
	messages = [
		"Oops, it looks like something went wrong.",
		"Your results couldn't be calculated, make sure you didn't mix up samples and conversions."
	]

	@test.assertSelectorHasText '.alert-error', message for message in messages

casper.run ->
	@test.done()
