casper.start 'http://localhost:8080/embed.php?cc=200&sc=1&ce=300&se=1'

casper.then ->
	@wait 1000, ->
		@test.assertVisible '.alert-error', 'Error message loads'

casper.then ->
	messages = [
		"Oops, it looks like something went wrong.",
		"Your results couldn't be calculated, make sure you didn't mix up samples and conversions."
	]

	@test.assertSelectorHasText '.alert-error', message for message in messages

casper.run()
