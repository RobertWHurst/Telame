// I'm sure you will want to change this, put it somewhere different, or whatever, but for now it works ;)
function changeImg() {
	document.getElementById("notifications").src = (document.getElementById("notifications").src.indexOf("icons/asterisk_yellow.png") == -1) ? "/img/icons/asterisk_yellow.png" : "/img/icons/asterisk_orange.png";
}


//OJBECT MODUES
$(function($) {

	$("a").click(function(event) {
		document.cookie = 'CakeCookie[here]=' + event.target + "; path=/";
	});

	core = {

		//save the current domain
		'domain': 'http://' + window.location.hostname,

		//count the number of items in an object
		'sizeOf': function(object) {

			if (typeof object == 'object') {

				var size = 0;

				for (k in object) {

					if (object.hasOwnProperty(k)) {
						size += 1;
					}

				}

				return size;


			} else {
				return false;
			}

		},

		//shows a modal screen with a callback for on click of the screen itself
		'showModalScreen': function(modal, callback, agruments) {

			//append the modal
			$('body').append(modal);

			//save selectors
			var s = {};
			s.modalScreen = $('#modal_screen');

			var defaults = {
				'speed': 0,
				'animation': ''
			};

			//parse agruments
			var options = core.parseAguments(defaults, agruments);

			//start a process to fit the screen to the document
			var fitProc = function() {

				var documentElement = $(document);

				s.modalScreen.height(documentElement.height());
				s.modalScreen.width(documentElement.width());

			};
			loop.newProcess('modaleScreenPos', fitProc, 1);

			//show modal screen
			switch (options.animation) {

				case 'fade':
					s.modalScreen.fadeIn(options.speed);
					break;

				case 'slide':
					s.modalScreen.slideDown(options.speed);
					break;

				default:
					s.modalScreen.show(options.speed);
					break;
			}

			//bind a click event to the modal
			s.modalScreen.click(function(event) {

				//hide modal screen
				switch (options.animation) {

					default:
						s.modalScreen.hide(options.speed);
						break;

					case 'fade':
						s.modalScreen.fadeOut(options.speed);
						break;

					case 'slide':
						s.modalScreen.slideUp(options.speed);
						break;

				}

				//end the positioning process
				loop.killProcess('modaleScreenPos');

				//execute the callback
				callback(event);

			});

		},

		//take to arrays and
		'parseAguments': function(defaultsArray, newValuesArray, discardUnset) {

			//make sure both the defaults array and the new values array is set.
			//the also must be an array or an object.

			if (
					typeof defaultsArray !== 'object' && typeof defaultsArray !== 'array' ||
							typeof newValuesArray !== 'object' && typeof newValuesArray !== 'array'
					) {
				return false;
			}

			//check to see if values not set in the defaults array should
			//be discarded, otherwise set it to false.
			discardUnset = (typeof discardUnset === 'undefined');

			//copy the defaults
			var results = defaultsArray;

			//define loop vars
			var newValue, defaultValue, subdefaultsArray, newSubvaluesArray;

			//loop
			for (var key in newValuesArray) {

				//save the current argument
				newValue = newValuesArray[key];
				defaultValue = defaultsArray[key];

				//if we are to discard unset values and the value does not exist
				//skip (discard) it.
				if (typeof defaultValue === 'undefined' && typeof discardUnset === true) {
					continue;
				}

				//check to see if the current argument is acually an array or object
				if (typeof newValue === 'object' || typeof newValue === 'array') {

					//if we are to key unset arrays and objects and the object or array
					// does not exist or is not an array over write the default (keep it).
					if (typeof defaultValue !== 'object' && typeof defaultValue !== 'array' && typeof discardUnset === true) {

						//save the array
						results[key] = newValue[key];
						continue;
					}

					//if we are to discard unset arrays or objects and the default is not
					//and object or array skip (discard) it.
					if (typeof defaultValue !== 'object' || typeof defaultValue !== 'array') {
						continue;
					}

					//if the default value is actually an object or array and the new
					//value is an object or array then invoke recursion to parse the
					//new array or object against the default.
					subdefaultsArray = defaultValue;
					newSubvaluesArray = newValue;

					results[key] = core.parseAguments(subdefaultsArray, newSubvaluesArray, discardUnset);

				}

				//if the current value is not an object or array
				else {

					//overwrite the default with the new value
					results[key] = newValue;

				}
			} //end of for-in

			return results;
		}
	};

	//CONSTRUCTORS
	construct = {
		//LOOP
		'loop': function() {

			//process Array
			this.stack = {};

			//save this
			_loop = this;

			//add a new process
			this.newProcess = function(key, callback, interval) {
				//make sure that the callback is real and the interval is numeric
				if ($.isFunction(callback) === false || typeof interval !== 'number') {
					return false;
				}

				//add the process to the loop stack
				_loop.stack[key] = {'callback': callback, 'interval': interval, 'i': 0};
			}

			this.killProcess = function(key) {
				delete _loop.stack[key];
			}

			//the run switch for the loop
			this.isActive = true;

			//the function the executes the loop
			this.runtime = function() {

				//if the "isActive switch is false skip a cycle
				if (_loop.isActive !== true) {
					return;
				}
				else {
					for (stackKey in _loop.stack) {

						//take the process out of the stack
						var process = _loop.stack[stackKey];

						if (process.i === 0) {
							//if the process internal iderator is at zero run the process
							//and reset the internal iderator back to the use set interval
							process.callback();
							process.i = process.interval;
						}
						else {
							//remove one from the internal iderator
							process.i -= 1;
						}

						//put the process back in the stack
						_loop.stack[stackKey] = process;

					}
				}
			}

			//start the runtime loop
			setInterval(this.runtime, 0);

		}
	}

	//create the loop
	loop = new construct.loop;

	// the flash logic
	flashLogic = function() {

		//set the root for easy access
		var root = this;

		//store the selectors
		root.flashWrap = $('#flashWrapper');
		root.flash = $('#flash');
		root.flashMessages = $('div', '#flash');

		//create a function for displaying a flash message via javascript
		root.setMessage = function(key, message) {

			//create the message within a jquery object
			var message = $('<div class="' + key + '">' + message + '</div>');

			//hide the message
			message.hide();

			//append it the the flash container
			root.flashWrap.children('#flash').prepend(message);

			//animate it in
			message.slideDown(600);

			//after ten seconds hide the messages
			setTimeout(function() {
				message.slideUp(600, function() {
					$(this).remove();
				});
			}, 3000);

		}

		//create a handler for closing messages manually
		root.closeHandler = function() {

			root.flash.delegate('div', 'click', function() {

				var domElement = $(this);

				domElement.slideUp(300, function() {
					$(this).remove();
				});

			});
		}

		//create a constructor
		root.construct = function() {

			//set the flash wrapper to fixed position
			root.flashWrap.css({
				'position': 'fixed',
				'top': 0,
				'width': '100%',
				'opacity': 0.9,
				'z-index': 9999,
				'cursor': 'pointer'
			});

			//slide down the messages
			root.flashMessages.hide().slideDown(600);

			//after ten seconds hide the messages
			setTimeout(function() {
				root.flashMessages.slideUp(600, function() {
					$(this).remove();
				});
			}, 10000);

			//execute the handlers
			root.closeHandler();

		}

		root.construct();
	}

	flash = new flashLogic;
});

//EXPANDING TEXTAREA PLUGIN
(function($) {

	/*
	 * Auto-growing textareas;
	 */
	$.fn.autogrow = function(options) {

		this.filter('textarea').each(function() {

			var $this = $(this),
					minHeight = $this.height(),
					lineHeight = $this.css('lineHeight');

			var shadow = $('<div class="autogrow_shadow"></div>').css({
				position: 'absolute',
				top: -10000,
				left: -10000,
				width: $(this).width() - parseInt($this.css('paddingLeft')) - parseInt($this.css('paddingRight')),
				fontSize: $this.css('fontSize'),
				fontFamily: $this.css('fontFamily'),
				lineHeight: $this.css('lineHeight'),
				resize: 'none'
			}).appendTo(document.body);

			var update = function() {

				var times = function(string, number) {
					for (var i = 0, r = ''; i < number; i ++) r += string;
					return r;
				};

				var val = this.value.replace(/</g, '&lt;')
						.replace(/>/g, '&gt;')
						.replace(/&/g, '&amp;')
						.replace(/\n$/, '<br/>&nbsp;')
						.replace(/\n/g, '<br/>')
						.replace(/ {2,}/g, function(space) {
					return times('&nbsp;', space.length - 1) + ' '
				});

				shadow.html(val);
				$(this).css('height', Math.max(shadow.height() + 20, minHeight));

			}

			$(this).change(update).keyup(update).keydown(update);

			update.apply(this);

		});

		return this;

	}

})(jQuery);


/**
 *
 * credits for this plugin go to brandonaaron.net
 *
 * unfortunately his site is down
 *
 * @param {Object} up
 * @param {Object} down
 * @param {Object} preventDefault
 */
jQuery.fn.extend({
	mousewheel: function(up, down, preventDefault) {
		return this.hover(
		                 function() {
			                 jQuery.event.mousewheel.giveFocus(this, up, down, preventDefault);
		                 },
		                 function() {
			                 jQuery.event.mousewheel.removeFocus(this);
		                 }
				);
	},
	mousewheeldown: function(fn, preventDefault) {
		return this.mousewheel(function() {
		}, fn, preventDefault);
	},
	mousewheelup: function(fn, preventDefault) {
		return this.mousewheel(fn, function() {
		}, preventDefault);
	},
	unmousewheel: function() {
		return this.each(function() {
			jQuery(this).unmouseover().unmouseout();
			jQuery.event.mousewheel.removeFocus(this);
		});
	},
	unmousewheeldown: jQuery.fn.unmousewheel,
	unmousewheelup: jQuery.fn.unmousewheel
});


jQuery.event.mousewheel = {
	giveFocus: function(el, up, down, preventDefault) {
		if (el._handleMousewheel) jQuery(el).unmousewheel();

		if (preventDefault == window.undefined && down && down.constructor != Function) {
			preventDefault = down;
			down = null;
		}

		el._handleMousewheel = function(event) {
			if (!event) event = window.event;
			if (preventDefault)
				if (event.preventDefault) event.preventDefault();
				else event.returnValue = false;
			var delta = 0;
			if (event.wheelDelta) {
				delta = event.wheelDelta / 120;
				if (window.opera) delta = -delta;
			} else if (event.detail) {
				delta = -event.detail / 3;
			}
			if (up && (delta > 0 || !down))
				up.apply(el, [event, delta]);
			else if (down && delta < 0)
				down.apply(el, [event, delta]);
		};

		if (window.addEventListener)
			window.addEventListener('DOMMouseScroll', el._handleMousewheel, false);
		window.onmousewheel = document.onmousewheel = el._handleMousewheel;
	},

	removeFocus: function(el) {
		if (!el._handleMousewheel) return;

		if (window.removeEventListener)
			window.removeEventListener('DOMMouseScroll', el._handleMousewheel, false);
		window.onmousewheel = document.onmousewheel = null;
		el._handleMousewheel = null;
	}
};

/*
 * Special event for image load events
 * Needed because some browsers does not trigger the event on cached images.

 * MIT License
 * Paul Irish     | @paul_irish | www.paulirish.com
 * Andree Hansson | @peolanha   | www.andreehansson.se
 * 2010.
 *
 * Usage:
 * $(images).bind('load', function (e) {
 *   // Do stuff on load
 * });
 *
 * Note that you can bind the 'error' event on data uri images, this will trigger when
 * data uri images isn't supported.
 *
 * Tested in:
 * FF 3+
 * IE 6-8
 * Chromium 5-6
 * Opera 9-10
 */
(function ($) {
	$.event.special.load = {
		add: function (hollaback) {
			if (this.nodeType === 1 && this.tagName.toLowerCase() === 'img' && this.src !== '') {
				// Image is already complete, fire the hollaback (fixes browser issues were cached
				// images isn't triggering the load event)
				if (this.complete || this.readyState === 4) {
					hollaback.handler.apply(this);
				}

				// Check if data URI images is supported, fire 'error' event if not
				else if (this.readyState === 'uninitialized' && this.src.indexOf('data:') === 0) {
					$(this).trigger('error');
				}

				else {
					$(this).bind('load', hollaback.handler);
				}
			}
		}
	};
}(jQuery));
