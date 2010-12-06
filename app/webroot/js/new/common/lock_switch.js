(function($) {

	//switches object
	var switches,markup,SlideOffsets,ToggleOffsets,states,methods;
	switches = {};
	markup = '' +
			'<div class="TS_LockSwitch">' +
			'<div class="slideTarget"></div>' +
			'<div class="toggleTarget"></div>' +
			'<div class="frame"></div>' +
			'<div class="slideWrapper"><div class="slide"></div></div>' +
			'<div class="toggleWrapper"><div class="toggle"></div></div>' +
			'</div>';
	SlideOffsets = {
		'block': -136,
		'allow': -76,
		'inherit-block': -213,
		'inherit-allow': 2
	};
	ToggleOffsets = {
		'block': 2,
		'allow': -21,
		'inherit-block': -44,
		'inherit-allow': -67
	}
	states = {
		'0': 'block',
		'1': 'allow',
		'2': 'inherit'
	};
	methods = {

		/*
		 * -- CREATES THE SWITCH ON LOAD --
		 */
		'init': function(args) {

			//default options
			var options;
			options = {
				'parent': false,
				'speed': 200,
				'callback': function() {
				}
			};

			// merge the options with the args
			$.fn.extend(options, args);

			//declare the switch
			$(this).each(function() {

				//get the current object
				var Obj,slide;
				Obj = $(this);
				slide = $('div.slide', Obj);

				//validate the markup
				if (! validate(Obj)) {
					return false;
				}

				//register the switch
				options = register(Obj, options);

				//create the switch markup
				buildMarkup(Obj);

				//update the state
				updateSlide(Obj, true);

				//bind a hover event
				mouseEvent(Obj);

			});

		}
	};


	function register(Obj, options) {
		var id;

		//make sure the parent is a jquery object
		if( options.parent && typeof options.parent !== 'object' ) {
			options.parent = $(options.parent);
		}

		//grab the obj id
		id = Obj.attr('id');

		//make sure the switch is not already registered
		if (typeof switches[id] === 'undefined') {

			//push the id and the options
			switches[id] = options;

			//the item was registered so return true
			return options;
		}

		//if the switch is registered then return false
		return false;

	}


	function validate(Obj) {

		//

		//FIXME: make sure the mark up is correct
		return true;

	}


	function buildMarkup(Obj) {

		var options, Widget;
		options = getOptions(Obj);

		//hide the objects children
		Obj.children().hide();

		//append the new markup
		Obj.prepend(markup);

		//capture the Widget
		Widget = $('div.TS_LockSwitch', Obj);

		if (options.parent) {
			Widget.addClass('child');
		} else {
			Widget.addClass('parent');
		}

	}


	function updateSlide(Objs, instant) {

		Objs.each(function(){
			var Obj = $(this);
			//get the current value
			var slideState,ToggleState,options;
			options = getOptions(Obj);
			slideState = getState(Obj);
			if( ! slideState.match('inherit-')){
				ToggleState = getState(getTopParent(Obj, true));
			} else {
				ToggleState = 'inherit-' + getState(getTopParent(Obj, true));
			}

			//align the slide to the correct state
			if (!instant) {
				$('div.slide', Obj).stop().animate({ 'left': SlideOffsets[slideState] }, options.speed);
				$('div.toggle', Obj).stop().animate({ 'left': ToggleOffsets[ToggleState] }, options.speed);
			} else {
				$('div.slide', Obj).css({ 'left': SlideOffsets[slideState] });
				$('div.toggle', Obj).css({ 'left': ToggleOffsets[ToggleState] }, options.speed);
			}
		});
	}


	function getState(Obj, noPrefix) {

		//get the current radio
		var currentRadioInput, curState;
		currentRadioInput = $('input:radio:checked', Obj);

		//get the input class
		for (var k in states) {
			if (currentRadioInput.val() == k) {
				curState = states[k];
				break;
			}
		}

		//if inherit
		if (curState == 'inherit') {

			var parent;
			parent = getOptions(Obj).parent;

			curState = getState(parent, true);

			if (! noPrefix) {
				curState = 'inherit-' + curState;
			}

		}

		return curState;
	}


	function getOptions(Obj) {

		//make sure this is a switch
		if( ! Obj.attr ){
			return false;
		}

		//grab the obj id
		var id;
		id = Obj.attr('id');

		return switches[id];

	}


	function getAllParents(Obj){
		var options;
		options = getOptions(Obj);
		if ( ! options || ! options.parent ) {
			return Obj;
		} else {
			return Obj.add(getTopParent(options.parent));
		}
	}


	function getTopParent(Obj, ignoreOverRides) {
		var options,overRiden;
		options = getOptions(Obj);

		if( ! ignoreOverRides ){
			overRiden = getState( Obj ).match('inherit-');
		} else {
			overRiden = true;
		}

		if ( ! options || ! options.parent || ! overRiden) {
			return Obj;
		} else {
			return getTopParent(options.parent);
		}
	}


	function mouseEvent(Obj) {

		var parents,targets,state,value;
		parents = getAllParents(Obj);
		targets = Obj.add(parents).find('div.slideTarget');

		targets.mouseenter(function(){
			var options,slidePos,slideState,slide;

			if( $(this)[0] == $( 'div.slideTarget', getTopParent(Obj))[0]){
				options = getOptions(Obj);
				slide = $('div.slide', Obj);
				slideState = getState(Obj);
				slidePos = SlideOffsets[slideState];

				//add the hover
				$(this).addClass('hover');

				//nudge the slider to the next state just a bit
				switch (slideState) {
					case 'allow':
					case 'inherit-allow':
						slide.stop().animate({ 'left': slidePos - 20 + 'px' }, options.speed);
						break;
					case 'block':
					case 'inherit-block':
						slide.stop().animate({ 'left': slidePos + 20 + 'px' }, options.speed);
						break;
				}
			}
		});
		targets.mouseleave(function(){
			//strip the hover class
			$(this).removeClass('hover');

			//reset the slide pos
			updateSlide(Obj);
		});

		targets.click(function(){

			if( $(this)[0] == $( 'div.slideTarget', getTopParent(Obj))[0]){
				state = getState(Obj);
				switch(state){
					case 'allow':
						state = 'block';
						break;
					case 'block':
						state = 'allow';
						break;
				}
				for( var index in states ){
					if( states[index] === state ){
						value = index;
						break;
					}
				}
				$('input:radio[value="' + value + '"]', Obj).click();
			}
			updateSlide(Obj);
		});


	}


	$.fn.lockSwitch = function(method) {

		if (methods[method]) {

			return methods[method].apply(this, Array.prototype.slice.call(arguments, 1));

		} else if (typeof method === 'object' || ! method) {

			return methods.init.apply(this, arguments);

		} else {
			$.error('Method "' + method + '" does not exist on jQuery.lockSwitch');
		}
	}

})(jQuery);