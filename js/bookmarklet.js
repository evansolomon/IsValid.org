(function(){
	// Load jQuery if it isn't here
	if ( window.jQuery === undefined ) {
		var done = false,
			script = document.createElement("script");

		script.src = "//ajax.googleapis.com/ajax/libs/jquery/1.8.1/jquery.min.js";
		script.onload = script.onreadystatechange = function() {
			if ( ! done && ( ! this.readyState || this.readyState == "loaded" || this.readyState == 'complete' ) ) {
				done = true;
				init();
			}
		};
		document.getElementsByTagName('head')[0].appendChild( script );
	}
	else {
		init();
	}

	function fancyClickThing( event ) {
		var text = event.target.innerHTML;
		var number = parseInt( text.replace( ',', '' ), 10 );
		window.isvalidClicks.push( number );
		jQuery(event.target).css( 'background-color', 'rgba(50, 200, 80, 0.7)' ).addClass( 'isvalid-clicked' );

		if ( 4 == window.isvalidClicks.length ) {
			jQuery(this).unbind( 'mouseup', fancyClickThing );

			window.open( 'http://isvalid.org/' +
				'?sc=' + window.isvalidClicks[0] +
				'&cc=' + window.isvalidClicks[1] +
				'&se=' + window.isvalidClicks[2] +
				'&ce=' + window.isvalidClicks[3]
			);

			reset();
		}
	}

	function init() {
		window.isvalidClicks = [];
		window.isvalidBookmarklet = (function() {
			jQuery( document ).mouseup( fancyClickThing );
		})();

		jQuery('*').hover(
			function( event ) {
				var element = jQuery(this);
				if ( element.hasClass( 'isvalid-clicked' ) )
					return;

				event.stopPropagation();
				var text = event.target.innerHTML;
				var number = parseInt( text.replace( ',', '' ), 10 );
				if ( ! isNaN( parseFloat( number ) ) && isFinite( number ) )
					element.css( 'background-color', 'rgba(50, 200, 80, 0.25)' );
			},

			function( event ) {
				event.stopPropagation();
				var element = jQuery(this);
				if ( ! element.hasClass( 'isvalid-clicked' ) )
					element.css( 'background-color', '' );
			}
		);

	}

	function reset() {
		jQuery('.isvalid-clicked').removeClass( 'isvalid-clicked' ).css( 'background-color', '' );
		window.isvalidClicks = [];
	}
})();
