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

$ -> conconjr()
