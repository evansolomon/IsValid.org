casper.start 'http://localhost:8080/?cc=200&sc=1000&ce=300&se=2000'

casper.then ->
	@test.assertTitle 'IsValid | Quantify the results of A/B tests', 'HTML title'
	@test.assertNotVisible 'h1', 'Header is not visible'

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

casper.run ->
	@test.done()
