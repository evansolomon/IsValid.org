(function(){
	// Load jQuery if it isn't here
	if ( window.jQuery === undefined ) {
		var script = document.createElement("script");

		script.src = "//ajax.googleapis.com/ajax/libs/jquery/1.8.1/jquery.min.js";
		script.onload = script.onreadystatechange = function() {
			if ( ! this.readyState || this.readyState == "loaded" || this.readyState == 'complete' )
				init();
		};

		document.getElementsByTagName('head')[0].appendChild( script );
	}
	else {
		init();
	}

	function fancyClickThing( event ) {
		var text = event.target.innerHTML,
			number = parseInt( text.replace( ',', '' ), 10 );

		window.isvalidClicks.push( number );
		jQuery(event.target).css( 'background-color', 'rgba(50, 200, 80, 0.7)' ).addClass( 'isvalid-clicked' );

		if ( 4 == window.isvalidClicks.length ) {
			window.open( 'http://isvalid.org/' +
				'?sc=' + window.isvalidClicks[0] +
				'&cc=' + window.isvalidClicks[1] +
				'&se=' + window.isvalidClicks[2] +
				'&ce=' + window.isvalidClicks[3]
			);

			reset();
		}
	}

	function fancyMouseEnter( event ) {
		var element = jQuery(this);
		if ( element.hasClass( 'isvalid-clicked' ) )
			return;

		event.stopPropagation();
		var text = event.target.innerHTML,
			number = parseInt( text.replace( ',', '' ), 10 );

		if ( ! isNaN( parseFloat( number ) ) && isFinite( number ) )
			element.css( 'background-color', 'rgba(50, 200, 80, 0.25)' );
	}

	function fancyMouseLeave( event ) {
		var element = jQuery(this);
		if ( ! element.hasClass( 'isvalid-clicked' ) )
			element.css( 'background-color', '' );
	}

	function init() {
		reset(); // In case it gets run twice in a row

		window.isvalidClicks = [];
		jQuery( document ).mouseup( fancyClickThing );
		jQuery('*').hover( fancyMouseEnter, fancyMouseLeave );
	}

	function reset() {
		jQuery('.isvalid-clicked').removeClass( 'isvalid-clicked' ).css( 'background-color', '' );
		jQuery(document).unbind( 'mouseup', fancyClickThing );
		jQuery('*').unbind( 'mouseleave', fancyMouseLeave ).unbind( 'mouseenter', fancyMouseEnter );

		window.isvalidClicks = [];
	}
})();
