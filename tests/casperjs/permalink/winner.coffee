casper.start 'http://localhost:8080/?cc=500&sc=1000&ce=90&se=200&significance=0.9'

casper.then ->
	@wait 2000, ->
		@test.assertTruthy @evaluate ->
			$( '.result' ).first().hasClass 'winner'

casper.then ->
	@click '#conconjr'
	@sendKeys '#significance', '5'
	@wait 2000, ->
		@test.assertFalsy @evaluate ->
			$( '.results' ).first().hasClass 'winner'

casper.run ->
  @test.done()
