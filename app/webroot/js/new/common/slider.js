(function($){

	var complexSliders = {};
	var buttonTypes,
		aniSpeed,
		markup,
		methods = {



		/*
		 * -- CREATES THE SWITCH ON LOAD --
		 */
		'init': function( args ) {
			// DEFAULT OPTIONS FOR ON LOAD
			var options = {

				'parent': null,
				'speed': 200,
				'disabled': false,
				'callback': function(){}

			};

			// merge the options with the args
			$.fn.extend( options, args );

			aniSpeed = options.speed;

			//logic per slider
			return this.each(function(){

				//stash the dom element within jquery
				var de = $( this );

				//remove the parent if only two inputs are present
				if( de.find( 'input:radio' ).length == 2 ){
					options.parent = false;
				}

				//if the markup passes validation
				if( methods.validate( de ) ){

					//if the slider has a parent
					if( options.parent ){
						buttonTypes = ['allow', 'block', 'inherit'];
						markup = '<div class="sb">' +
							'<!-- border -->' +
						'</div>' +
						'<div class="ss">' +
							'<!-- slider slides -->' +
							'<div class="allow"><p>Allow</p></div>' +
							'<div class="inherit"><p>Inherit</p></div>' +
							'<div class="block" style="margin-left: 26px;"><p>Block</p></div>' +
						'</div>' +
						'<div class="st">' +
							'<!-- slider targets -->' +
							'<div id="' + de.attr( 'id' ) + '-slide-allow" class="target-allow"></div>' +
							'<div id="' + de.attr( 'id' ) + '-slide-inherit" class="target-inherit"></div>' +
							'<div id="' + de.attr( 'id' ) + '-slide-block" class="target-block"></div>' +
						'</div>';

					//if the slider has no parent
					} else {
						buttonTypes = ['allow', 'block'];
						markup = '<div class="sb">' +
							'<!-- border -->' +
						'</div>' +
						'<div class="ss">' +
							'<!-- slider slides -->' +
							'<div class="allow"><p>Allow</p></div>' +
							'<div class="block"><p>Block</p></div>' +
						'</div>' +
						'<div class="st">' +
							'<!-- slider targets -->' +
							'<div id="' + de.attr( 'id' ) + '-slide-allow" class="target-allow"></div>' +
							'<div id="' + de.attr( 'id' ) + '-slide-block" class="target-block"></div>' +
						'</div>';
					}

					//hide the original radio inputs
					de.children().hide();

					//add the new markup and class
					de.addClass( 'TS_slider' ).append( markup );

					//create a record for this switch
					complexSliders[de.attr( 'id' )] = {
						'value': null,
						'parent': null
					};

					//loop through the radio inputs and find the one that is active
					de.find( 'input:radio' ).each(function() {

						if( $( this ).attr( 'checked' ) ) {

							complexSliders[de.attr( 'id' )].value = $( this ).val();

							switch( $( this ).val() ){
								case $('input:radio.allow', de).val():
									$( 'div.ss', de ).children( 'div.inherit, div.block' ).css({ 'left': '68px' });
								break;
								case $('input:radio.inherit', de).val():
									$( 'div.ss', de ).children( 'div.block' ).css({ 'left': '68px' });
									$( 'div.ss', de ).children( 'div.inherit' ).css({ 'left': 0 });
								break;
								case $('input:radio.block', de).val():
									$( 'div.ss', de ).children( 'div.block' ).css({ 'left': 0 });
									$( 'div.ss', de ).children( 'div.inherit' ).css({ 'left': 0 });
								break;
							}

						}

					});

					if( options.parent ) {

						//select the parent
						if( typeof options.parent !== 'object' ) {
							options.parent = $( options.parent );
						}

						//make sure the parent is a slider
						if( typeof complexSliders[options.parent.attr('id')] !== 'undefined' ) {

							//prevent looped relationships
							if( complexSliders[options.parent.attr( 'id' )].parent !== de ) {

								//save the parent
								complexSliders[de.attr( 'id' )].parent = options.parent;

							} else {

								$.error( 'Complex slider "' + de.attr( 'id' ) + '" cannot be the parent of "' + options.parent.attr('id') + '"' );

							}

						} else {

							$.error( 'Complex slider "' + de.attr( 'id' ) + '" parent was invalid.' );

						}
					}

				} else {

					$.error( 'Could not create complex slider. The existing input group is missing or incorrect.' );

				}

				methods.alignTargets( de );
				methods.hoverHandler( de );
				methods.clickHandler( de );

		 	});

		 },



		/*
		 * Aligns the click targets with the sliders
		 */
		'alignTargets': function( de ){

			var key, left, marginLeft, targetOffset;

			//set up the targets
			for(key in buttonTypes) {
				if( buttonTypes[key] !== 'allow' ){
					targetOffset = 16;
				} else {
					targetOffset = 0;
				}
				left = $( 'div.ss', de ).find( 'div.' + buttonTypes[key] ).position().left;
				marginLeft = parseInt( $( 'div.ss', de ).find( 'div.' + buttonTypes[key] ).css( 'margin-left' ) ) + targetOffset;
				$( 'div.st', de ).find( 'div.target-' + buttonTypes[key] ).css( 'margin-left', marginLeft ).css( 'left', left );
			}
		},



		/*
		 * CREATES THE CLICK EVENTS FOR TARGETS
		 */
		'clickHandler': function( de ){

			var key;

			//setup the click events
			for(key in buttonTypes) {

				//bind events
				$( 'div.st', de ).find( 'div.target-' + buttonTypes[key] ).click(function(){
					//grab the clicked target
					var targetId = $( this ).attr('id');
					//get the affected slide class
					var slideClass = targetId.replace( de.attr( 'id' ) + '-slide-', '' );
					//get the value of the current slide
					var currentValue = $('input:radio.' + slideClass, de).val();

					//make sure that the clicked slider is not already selected
					if( de.complexSlider( 'value' ) !== currentValue ){

						//pass the class as the new value
						de.complexSlider('value', currentValue);

					} else {
						if( $( 'div.ss', de ).children( 'div' ).length > 2 ){
							if(slideClass == 'block'){
								$( 'div.st', de ).find( 'div.target-inherit' ).click();
							} else if(slideClass == 'inherit'){
								$( 'div.st', de ).find( 'div.target-allow' ).click();
							} else if(slideClass == 'allow'){
								$( 'div.st', de ).find( 'div.target-block' ).click();
							}
						} else {
							if(slideClass == 'block'){
								$( 'div.st', de ).find( 'div.target-allow' ).click();
							} else if(slideClass == 'allow'){
								$( 'div.st', de ).find( 'div.target-block' ).click();
							}
						}
					}
				});
			}
		},



		/*
		 * THE HOVER HANDLER
		 */
		'hoverHandler': function( de ){

			var key;

			//setup the click events
			for(key in buttonTypes) {

				//bind events
				$( 'div.st', de ).find( 'div.target-' + buttonTypes[key] ).hover(function(){

					var pos;

					//grab the hovered target
					var target = $(this);
					var targetId = target.attr( 'id' );
					//get the affected slide
					var slideClass = targetId.replace( de.attr( 'id' ) + '-slide-', '' );
					var slide = $( 'div.ss', de ).find( 'div.' + slideClass );

					if( ! de.hasClass( 'disabled' ) ){

						//add the hover class
						slide.addClass( 'hover' );

						//animate the other slides
						switch( slideClass ){
							case 'allow':
								if( complexSliders[de.attr('id')].value === $('input:radio.inherit', de).val() ){
									slide.siblings( 'div.inherit' ).stop().animate({ 'left': '16px' }, aniSpeed);
								} else if( complexSliders[de.attr('id')].value === $('input:radio.block', de).val() ){
									slide.siblings( 'div.inherit, div.block' ).stop().animate({ 'left': '16px' }, aniSpeed);
								}
							break;
							case 'inherit':
								if( complexSliders[de.attr('id')].value === $('input:radio.allow', de).val() ){
									slide.stop().animate({ 'left': '52px' }, aniSpeed);
								} else if( complexSliders[de.attr('id')].value === $('input:radio.block', de).val() ){
									slide.siblings( 'div.block' ).stop().animate({ 'left': '16px' }, aniSpeed);
								}
							break;
							case 'block':
								if( complexSliders[de.attr('id')].value === $('input:radio.allow', de).val() ){
									slide.stop().animate({ 'left': '52px' }, aniSpeed);
									slide.siblings( 'div.inherit' ).stop().animate({ 'left': '52px' }, aniSpeed);
								} else if( complexSliders[de.attr('id')].value === $('input:radio.inherit', de).val() ){
									slide.stop().animate({ 'left': '52px' }, aniSpeed);
								}
							break;
						}

					}

				}, function(){

					//grab the hovered target
					var target = $( this );
					var targetId = target.attr( 'id' );
					//get the affected slide
					var slideClass = targetId.replace( de.attr( 'id' ) + '-slide-', '' );
					var slide = $( 'div.ss', de ).find( 'div.' + slideClass );

					slide.removeClass( 'hover' );

					switch( slideClass ){
						case 'allow':
							if( complexSliders[de.attr('id')].value === $('input:radio.allow', de).val() ){
								slide.siblings( 'div.inherit, div.block' ).stop().animate({ 'left': '68px' }, aniSpeed);
							} else if( complexSliders[de.attr('id')].value === $('input:radio.inherit', de).val() ){
								slide.siblings( 'div.block' ).stop().animate({ 'left': '68px' }, aniSpeed);
								slide.siblings( 'div.inherit' ).stop().animate({ 'left': 0 }, aniSpeed);
							} else if( complexSliders[de.attr('id')].value === $('input:radio.block', de).val() ){
								slide.siblings( 'div.block' ).stop().animate({ 'left': 0 }, aniSpeed);
								slide.siblings( 'div.inherit' ).stop().animate({ 'left': 0 }, aniSpeed);
							}
						break;
						case 'inherit':
							if( complexSliders[de.attr('id')].value === $('input:radio.block', de).val() ){
								slide.stop().animate({ 'left': 0 }, aniSpeed);
								slide.siblings( 'div.block' ).stop().animate({ 'left': 0 }, aniSpeed);
							} else if( complexSliders[de.attr('id')].value === $('input:radio.inherit', de).val() ){
								slide.stop().animate({ 'left': 0 }, aniSpeed);
								slide.siblings( 'div.block' ).stop().animate({ 'left': '68px' }, aniSpeed);
							} else if( complexSliders[de.attr('id')].value === $('input:radio.allow', de).val() ){
								slide.stop().animate({ 'left': '68px' }, aniSpeed);
								slide.siblings( 'div.block' ).stop().animate({ 'left': '68px' }, aniSpeed);
							}
						break;
						case 'block':
							if( complexSliders[de.attr('id')].value === $('input:radio.allow', de).val() ){
								slide.stop().animate({ 'left': '68px' }, aniSpeed);
								slide.siblings( 'div.inherit' ).stop().stop().animate({ 'left': '68px' }, aniSpeed);
							} else if( complexSliders[de.attr('id')].value === $('input:radio.inherit', de).val() ){
								slide.stop().animate({ 'left': '68px' }, aniSpeed);
								slide.siblings( 'div.inherit' ).stop().animate({ 'left': 0 }, aniSpeed);
							} else if( complexSliders[de.attr('id')].value === $('input:radio.block', de).val() ){
								slide.stop().animate({ 'left': 0 }, aniSpeed);
								slide.siblings( 'div.inherit' ).stop().animate({ 'left': 0 }, aniSpeed);
							}
						break;
					}

				});
			}
		},


		/*
		 * VALIDATES THE MARKUP REQUIRED TO CREATE A COMPLEX SLIDER RETURNS TRUE OR FALSE
		 */
		'validate': function( de ) {

			//make sure the dom element has at least two radio inputs
			if( de.find( 'input:radio' ).length > 1 ) {

				//grab the name of the current input to create a name var to compare to
				var lastName = de.find( 'input:radio' ).eq( 0 ).attr( 'name' );
				var requiredClasses;

				if( de.find( 'input:radio' ).length === 3 ){
					//set the required ids
					requiredClasses = {
						'allow': false,
						'inherit': false,
						'block': false
					};
				} else if( de.find( 'input:radio' ).length == 2 ){
					//set the required ids
					requiredClasses = {
						'allow': false,
						'block': false
					};
				}

				//for each of the radio inputs compare the name of the input to the last
				de.find( 'input:radio' ).each(function() {

					var key;
					for( key in requiredClasses ){
						if( $( this ).hasClass( key ) ){
							requiredClasses[key] = true;
						}
					}

					//grab the name of the current input
					var name = $( this ).attr( 'name' );

					//if the last name and the current name do not match break and throw an error
					if( name != lastName ) {
						return false;
					}

					//save the name of the current input for the next loop
					lastName = name;

				});
				var key;
				for( key in requiredClasses ) {

					if( requiredClasses[key] === false ) {
						return false;
					}

				}

				return true;

			} else {

				return false;

			}

		},



		/*
		 * SETS A NEW VALUE OR RETURNS THE CURRENT VALUE
		 */
		'value': function( newValue ) {

			//save the element
			var de = $( this );

			if( newValue ) {

				//make sure the radio inputs are not disabled
				if( ! de.hasClass( 'disabled' ) ){

					//loop through the rdio inputs and find the one that matches the given value
					de.find( 'input:radio' ).each(function() {

						if( $( this ).val() === newValue ) {
							//set the new value
							$( this ).attr( 'checked', 'checked' ).siblings( 'input:radio' ).removeAttr( 'checked' );

							complexSliders[de.attr( 'id' )].value = $( this ).val();

						}

					});

					switch( newValue ){

						case $('input:radio.allow', de).val():
							$( de, 'div.ss').find( 'div.inherit' ).stop().animate( { 'left': '68px' }, aniSpeed );
							$( de, 'div.st').find( 'div.target-inherit' ).stop().animate( { 'left': '68px' }, aniSpeed );
							$( de, 'div.ss').find( 'div.block' ).stop().animate( {'left': '68px' }, aniSpeed );
							$( de, 'div.st').find( 'div.target-block' ).stop().animate( {'left': '68px' }, aniSpeed );
						break;
						case $('input:radio.inherit', de).val():
							$( de, 'div.ss').find( 'div.inherit' ).stop().animate( {'left': 0 }, aniSpeed );
							$( de, 'div.st').find( 'div.target-inherit' ).stop().animate( {'left': 0 }, aniSpeed );
							$( de, 'div.ss').find( 'div.block' ).stop().animate( {'left': '68px' }, aniSpeed );
							$( de, 'div.st').find( 'div.target-block' ).stop().animate( {'left': '68px' }, aniSpeed );
						break;
						case $('input:radio.block', de).val():
							$( de, 'div.ss').find( 'div.inherit' ).stop().animate({'left': 0 }, aniSpeed );
							$( de, 'div.st').find( 'div.target-inherit' ).stop().animate({'left': 0 }, aniSpeed );
							$( de, 'div.ss').find( 'div.block' ).stop().animate( {'left': 0 }, aniSpeed );
							$( de, 'div.st').find( 'div.target-block' ).stop().animate( {'left': 0 }, aniSpeed );
						break;

					}
				}

			} else {

				return complexSliders[de.attr( 'id' )].value;

			}

		},
		/*
		 * ---------------------------------------------
		 */



		/*
		 * DISABLES THE SWITCH
		 */
		'disable': function() {

			//save the element
			de = $( this );

			de.addClass( 'disabled' ).find( 'input:radio' ).each(function() {

				$( this ).attr( 'disabled', 'disabled' );

			});

		},



		/*
		 * ENABLES THE SWITCH
		 */
		'enable': function() {

			//save the element
			de = $( this );

			//set all of the radio inputs to disabled
			de.removeClass( 'disabled' ).find( 'input:radio' ).each(function() {

				$( this ).removeAttr( 'disabled' );

			});

		},
		/*
		 * -------------------
		 */



		/*
		 * SETS A NEW PARENT OR RETURNS THE CURRENT PARENT ID
		 */
		'parent': function( parent ) {

			var de = $( this );

			if( parent ) {

				//select the parent
				if( typeof parent !== 'object' ) {
					parent = $( parent );
				}

				//make sure the parent is not the current switch
				if( parent.attr('id') !== de.attr('id') ){

					//prevent loop relationships
					if( parent.complexSlider('parent') !== de) {

						complexSliders[de.attr('id')].parent = parent;

					} else {

						$.error( 'Complex slider "' + de.attr('id') + '" cannot be the parent of "' + parent.attr('id') + '"' );

					}

				} else {

					$.error( 'Complex slider "' + de.attr('id') + '" cannot be its own parent.' );

				}

			} else {

				if( complexSliders[de.attr('id')].parent ) {
					return complexSliders[de.attr('id')].parent;
				} else {
					return false;
				}
			}
		}
	};



	$.fn.complexSlider = function( method ){

		if( methods[method] ) {

			return methods[method].apply( this, Array.prototype.slice.call( arguments, 1 ));

		} else if( typeof method === 'object' || ! method ) {

			return methods.init.apply( this, arguments );

		} else {
			$.error( 'Method "' + method + '" does not exist on jQuery.complexSlider' );
		}
	}


})( jQuery );