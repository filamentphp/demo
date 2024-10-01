// Sticky

/* Smart Resize  */
( function ( $, sr ) {
	'use strict';

	// debouncing function from John Hann
	// http://unscriptable.com/index.php/2009/03/20/debouncing-javascript-methods/
	var debounce = function ( func, threshold, execAsap ) {
		var timeout;

		return function debounced() {
			var obj = this, args = arguments;
			function delayed() {
				if ( !execAsap )
					func.apply( obj, args );
				timeout = null;
			}

			if ( timeout )
				clearTimeout( timeout );
			else if ( execAsap )
				func.apply( obj, args );

			timeout = setTimeout( delayed, threshold || 100 );
		};
	};
	// smartresize 
	jQuery.fn[ sr ] = function ( fn ) { return fn ? this.bind( 'resize', debounce( fn ) ) : this.trigger( sr ); };

} )( jQuery, 'smartresize' );

( function ( $ ) {
	'use strict';

	// jQuery Pin plugin
	$.fn.themePin = function ( options ) {
		var scrollY = 0, lastScrollY = 0, elements = [], disabled = false, $window = $( window ), fixedSideTop = [], fixedSideBottom = [], prevDataTo = [];

		options = options || {};

		var recalculateLimits = function () {
			for ( var i = 0, len = elements.length; i < len; i++ ) {
				var $this = elements[ i ];

				if ( options.minWidth && $window.width() <= options.minWidth ) {
					if ( $this.parent().is( ".pin-wrapper" ) ) { $this.unwrap(); }
					$this.css( { width: "", left: "", top: "", position: "" } );
					disabled = true;
					continue;
				} else {
					disabled = false;
				}

				var $container = options.containerSelector ? ( $this.closest( options.containerSelector ).length ? $this.closest( options.containerSelector ) : $( options.containerSelector ) ) : $( document.body );
				var offset = $this.offset();
				var containerOffset = $container.offset();

				if ( typeof containerOffset == 'undefined' ) {
					continue;
				}

				var parentOffset = $this.parent().offset();

				if ( !$this.parent().is( ".pin-wrapper" ) ) {
					$this.wrap( "<div class='pin-wrapper'>" );
				}

				var pad = $.extend( {
					top: 0,
					bottom: 0
				}, options.padding || {} );

				var pt = parseInt( $this.parent().parent().css( 'padding-top' ) ), pb = parseInt( $this.parent().parent().css( 'padding-bottom' ) );

				if ( typeof options.paddingOffsetTop != 'undefined' ) {
					pad.top += parseInt( options.paddingOffsetTop, 10 );
				} else {
					pad.top += 18;
				}
				if ( typeof options.paddingOffsetBottom != 'undefined' ) {
					pad.bottom = parseInt( options.paddingOffsetBottom, 10 );
				} else {
					pad.bottom = 0;
				}

				var bb = $this.css( 'border-bottom' ), h = $this.outerHeight();
				$this.css( 'border-bottom', '1px solid transparent' );
				var o_h = $this.outerHeight() - h - 1;
				$this.css( 'border-bottom', bb );
				$this.css( { width: $this.outerWidth() <= $this.parent().width() ? $this.outerWidth() : $this.parent().width() } );
				$this.parent().css( "height", $this.outerHeight() + o_h );

				if ( $this.outerHeight() <= $window.height() ) {
					$this.data( "themePin", {
						pad: pad,
						from: ( options.containerSelector ? containerOffset.top : offset.top ) - pad.top + pt,
						pb: pb,
						parentTop: parentOffset.top - pt,
						offset: o_h
					} );
				} else {
					$this.data( "themePin", {
						pad: pad,
						fromFitTop: ( options.containerSelector ? containerOffset.top : offset.top ) - pad.top + pt,
						from: ( options.containerSelector ? containerOffset.top : offset.top ) + $this.outerHeight() - $( window ).height() + pt,
						pb: pb,
						parentTop: parentOffset.top - pt,
						offset: o_h
					} );
				}
			}
		};

		var onScroll = function () {
			if ( disabled ) { return; }

			scrollY = $window.scrollTop();

			var window_height = window.innerHeight || $window.height();

			for ( var i = 0, len = elements.length; i < len; i++ ) {
				var $this = $( elements[ i ] ),
					data = $this.data( "themePin" ),
					sidebarTop;

				if ( !data ) { // Removed element
					continue;
				}

				var $container = options.containerSelector ? ( $this.closest( options.containerSelector ).length ? $this.closest( options.containerSelector ) : $( options.containerSelector ) ) : $( document.body ),
					isFitToTop = ( $this.outerHeight() + data.pad.top ) <= window_height;

				data.end = $container.offset().top + $container.height();
				if ( isFitToTop ) {
					data.to = $container.offset().top + $container.height() - $this.outerHeight() - data.pad.bottom - data.pb;
				} else {
					data.to = $container.offset().top + $container.height() - window_height - data.pb;
					data.to2 = $container.height() - $this.outerHeight() - data.pad.bottom - data.pb;
				}

				if ( prevDataTo[ i ] === 0 ) {
					prevDataTo[ i ] = data.to;
				}

				if ( prevDataTo[ i ] != data.to ) {
					if ( fixedSideBottom[ i ] && $this.height() + $this.offset().top + data.pad.bottom < scrollY + window_height ) {
						fixedSideBottom[ i ] = false;
					}
				}

				if ( isFitToTop ) {
					var from = data.from - data.pad.bottom,
						to = data.to - data.pad.top - data.offset;
					if ( typeof data.fromFitTop != 'undefined' && data.fromFitTop ) {
						from = data.fromFitTop - data.pad.bottom;
					}

					if ( from + $this.outerHeight() > data.end || from >= to ) {
						$this.css( { position: "", top: "", left: "" } );
						if ( options.activeClass ) { $this.removeClass( options.activeClass ); }
						continue;
					}
					if ( scrollY > from && scrollY < to ) {
						!( $this.css( "position" ) == "fixed" ) && $this.css( {
							left: $this.offset().left,
							top: data.pad.top
						} ).css( "position", "fixed" );
						if ( options.activeClass ) { $this.addClass( options.activeClass ); }
					} else if ( scrollY >= to ) {
						$this.css( {
							left: "",
							top: to - data.parentTop + data.pad.top
						} ).css( "position", "absolute" );
						if ( options.activeClass ) { $this.addClass( options.activeClass ); }
					} else {
						$this.css( { position: "", top: "", left: "" } );
						if ( options.activeClass ) { $this.removeClass( options.activeClass ); }
					}
				} else if ( ( $this.height() + data.pad.top + data.pad.bottom ) > window_height || fixedSideTop[ i ] || fixedSideBottom[ i ] ) {
					var padTop = parseInt( $this.parent().parent().css( 'padding-top' ) );
					// Reset the sideSortables style when scrolling to the top.
					if ( scrollY + data.pad.top - padTop <= data.parentTop ) {
						$this.css( { position: "", top: "", bottom: "", left: "" } );
						fixedSideTop[ i ] = fixedSideBottom[ i ] = false;

						if ( options.activeClass ) { $this.removeClass( options.activeClass ); }
					} else if ( scrollY >= data.to ) {
						$this.css( {
							left: "",
							top: data.to2,
							bottom: ""
						} ).css( "position", "absolute" );
						if ( options.activeClass ) { $this.addClass( options.activeClass ); }
					} else {

						// When scrolling down.
						if ( scrollY > lastScrollY ) {
							if ( fixedSideTop[ i ] ) {

								// Let it scroll.
								fixedSideTop[ i ] = false;
								sidebarTop = $this.offset().top - data.parentTop;

								$this.css( {
									left: "",
									top: sidebarTop,
									bottom: ""
								} ).css( "position", "absolute" );
								if ( options.activeClass ) { $this.addClass( options.activeClass ); }
							} else if ( !fixedSideBottom[ i ] && $this.height() + $this.offset().top + data.pad.bottom < scrollY + window_height ) {
								// Pin the bottom.
								fixedSideBottom[ i ] = true;

								!( $this.css( "position" ) == "fixed" ) && $this.css( {
									left: $this.offset().left,
									bottom: data.pad.bottom,
									top: ""
								} ).css( "position", "fixed" );
								if ( options.activeClass ) { $this.addClass( options.activeClass ); }
							}

							// When scrolling up.
						} else if ( scrollY < lastScrollY ) {
							if ( fixedSideBottom[ i ] ) {
								// Let it scroll.
								fixedSideBottom[ i ] = false;
								sidebarTop = $this.offset().top - data.parentTop;

								$this.css( {
									left: "",
									top: sidebarTop,
									bottom: ""
								} ).css( "position", "absolute" );
								if ( options.activeClass ) { $this.addClass( options.activeClass ); }
							} else if ( !fixedSideTop[ i ] && $this.offset().top >= scrollY + data.pad.top ) {
								// Pin the top.
								fixedSideTop[ i ] = true;

								!( $this.css( "position" ) == "fixed" ) && $this.css( {
									left: $this.offset().left,
									top: data.pad.top,
									bottom: ''
								} ).css( "position", "fixed" );
								if ( options.activeClass ) { $this.addClass( options.activeClass ); }
							} else if ( !fixedSideBottom[ i ] && fixedSideTop[ i ] && $this.css( 'position' ) == 'absolute' && $this.offset().top >= scrollY + data.pad.top ) {
								fixedSideTop[ i ] = false;
							}
						} else {
							fixedSideTop[ i ] = false;
							fixedSideTop[ i ] = false;
						}
					}
				} else {
					// If the sidebar container is smaller than the viewport, then pin/unpin the top when scrolling.
					if ( scrollY >= ( data.parentTop - data.pad.top ) ) {
						$this.css( {
							position: 'fixed',
							top: data.pad.top
						} );
					} else {
						$this.css( { position: "", top: "", bottom: "", left: "" } );
					}

					fixedSideTop[ i ] = fixedSideBottom[ i ] = false;
				}

				prevDataTo[ i ] = data.to;
			}

			lastScrollY = scrollY;
		};

		var recalculateLeft = function ( timeout ) {
			if ( typeof timeout == 'undefined' ) {
				timeout = 400;
			}
			for ( var i = 0, len = elements.length; i < len; i++ ) {
				var $this = $( elements[ i ] ),
					data = $this.data( "themePin" ),
					sidebarTop;

				if ( !data ) { // Removed element
					continue;
				}

				var $container = options.containerSelector ? ( $this.closest( options.containerSelector ).length ? $this.closest( options.containerSelector ) : $( options.containerSelector ) ) : $( document.body );

				if ( $this.css( "position" ) == "fixed" ) {
					var offset = $this.offset().top - $container.offset().top;
					$this.css( {
						'position': 'absolute',
						left: '',
						top: offset,
						bottom: ''
					} );
					setTimeout( function () {
						$this.css( {
							left: $this.offset().left,
							top: data.pad.top,
							bottom: ''
						} ).css( 'position', 'fixed' );
					}, timeout );
				}
			}
		};

		var update = function () { recalculateLimits(); onScroll(); };

		this.each( function () {
			var $this = $( this ),
				data = $( this ).data( 'themePin' ) || {};

			if ( data && data.update ) { return; }
			elements.push( $this );
			$( "img", this ).one( "load", recalculateLimits );
			data.update = update;
			$( this ).data( 'themePin', data );
			fixedSideTop.push( false );
			fixedSideBottom.push( false );
			prevDataTo.push( 0 );
		} );

		$window.on( 'touchmove scroll', onScroll );
		recalculateLimits();

		$window.on( 'load', update );

		$( this ).on( 'recalc.pin', function () {
			recalculateLimits();
			onScroll();
		} );

		$( this ).on( 'recalc.pin.left', function ( e, timeout ) {
			recalculateLeft( timeout );
		} );

		return this;
	};

	var instanceName = '__sticky';

	var Sticky = function ( $el, opts ) {
		return this.initialize( $el, opts );
	};

	Sticky.defaults = {
		autoInit: false,
		minWidth: 767,
		padding: {
			top: 0,
			bottom: 0
		},
		offsetTop: 0,
		offsetBottom: 0
	};

	Sticky.prototype = {
		initialize: function ( $el, opts ) {
			if ( $el.data( instanceName ) ) {
				return this;
			}

			this.$el = $el;

			this
				.setData()
				.setOptions( opts )
				.build();

			return this;
		},

		setData: function () {
			this.$el.data( instanceName, this );

			return this;
		},

		setOptions: function ( opts ) {
			this.options = $.extend( true, {}, Sticky.defaults, opts, {
				wrapper: this.$el
			} );

			return this;
		},

		build: function () {
			if ( !( $.isFunction( $.fn.themePin ) ) ) {
				return this;
			}

			var self = this,
				$el = this.options.wrapper,
				stickyResizeTrigger;

			$el.themePin( this.options );

			$( window ).smartresize( function () {
				if ( stickyResizeTrigger ) {
					clearTimeout( stickyResizeTrigger );
				}
				stickyResizeTrigger = setTimeout( function () {
					$el.trigger( 'recalc.pin' );
				}, 800 );

				var $parent = $el.parent();

				$el.outerWidth( $parent.width() );
				if ( $el.css( 'position' ) == 'fixed' ) {
					$el.css( 'left', $parent.offset().left );
				}
			} );

			return this;
		}
	};

	// jquery plugin
	$.fn.themeSticky = function ( opts ) {
		return this.map( function () {
			var $this = $( this );
			if ( $this.data( instanceName ) ) {
				$this.trigger( 'recalc.pin' );
				setTimeout( function () {
					$this.trigger( 'recalc.pin' );
				}, 800 );
				return $this.data( instanceName );
			} else {
				return new Sticky( $this, opts );
			}
		} );
	}
} ).apply( this, [ jQuery ] );