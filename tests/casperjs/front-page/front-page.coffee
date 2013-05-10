casper.start 'http://localhost:8080'

casper.then ->
	@test.assertTitle 'IsValid | Quantify the results of A/B tests', 'HTML title'
	@wait 1000, ->
		@test.assertVisible 'h1', 'Header is visible'

casper.then ->
	@wait 1000, ->
		@test.assertVisible '.alert-info', 'Info alert loads'

	@test.assertSelectorHasText '.alert-info', 'Results will automatically load when you fill in each field'

casper.run ->
  @test.done()
