casper.start 'http://localhost:8080/embed?cc=200&sc=1000&ce=300&se=2000'

casper.then ->
	@wait 1000, ->
		@test.assertVisible '.results', 'Results load'

casper.then ->
	averages = @evaluate ->
		_.map $('.average'), ( item ) ->
			$( item ).text()

	@test.assertEquals averages, [ '20%', '15%', '100%', '-25%' ]

casper.then ->
	ranges = @evaluate ->
		_.map $('.range'), ( item ) ->
			$( item ).text().trim()

	@test.assertEquals ranges, [ '15.8 – 24.2', '12.4 – 17.6', 'Original is better', '-34.6 – -15.4' ]

casper.then ->
	@test.assertNotVisible '.footer', 'No footer'
	@test.assertNotVisible '.header', 'No header'

casper.run()
