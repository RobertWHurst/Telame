$(function() {

	var thumbLogic = function() {

		//save this as root
		var root = this;

		//save the dom elements
		root.albums = $('#albums');
		root.album = $('div.album', '#albums');
		root.wrapThumb = $('div.wrap_thumb', '#albums');
		root.thumb = $('div.thumb', '#albums');
		root.image = $('div.image', '#albums');

		//the animation speed
		root.speed = 400;

		root.thumbSize = {
			'height': 167,
			'width': 167
		}

		//thumb handler
		root.thumbHandler = function() {

			root.albums.delegate('div.wrap_thumb', 'hover', function(event) {

				//grab the dom element
				domElement = $(this);

				//when the mouse enters the thumb
				if (event.type == 'mouseover') {

					timeout = setTimeout(function() {

						//hide the thumb and show the image
						domElement.children('div.thumb').fadeOut(root.speed);
						domElement.children('div.image').fadeIn(root.speed);

					}, root.speed);

				}

				//when the mouse leaves the thumb
				else {

					//clear any fade in timeouts
					clearTimeout(timeout);

					//hide the image and show the thumb
					domElement.children('div.thumb').fadeIn(root.speed);
					domElement.children('div.image').fadeOut(root.speed);

				}

			});

		}

		//define the function that moves the large image around
		root.positionZoom = function() {

			root.albums.delegate('div.wrap_thumb', 'mousemove', function(event) {

				//grab the dom element
				domElement = $(this);

				//save the image size
				var image = {
					'height': domElement.children('div.image').height(),
					'width': domElement.children('div.image').width()
				}

				//save the thumb size and offset
				var thumbOffset = domElement.offset();
				var thumb = {
					'height': root.thumbSize.height,
					'width': root.thumbSize.width,
					'top': Math.floor(thumbOffset.top),
					'left': Math.floor(thumbOffset.left)
				}

				//save the cursor pos
				var cursor = {
					'y': Math.round(event.pageY) - thumb.top,
					'x': Math.round(event.pageX) - thumb.left
				}

				// NOTE: this function uses cross mulipication to position the image within the thumb on zoom
				//
				// y = the cursor position from the top of the thumb
				// x = the cursor position from the left of the thumb
				//
				// h = the height of the thumb
				// w = the width of the thumb
				//
				// H = the height of the image
				// W = the width of the image
				//
				// T = the image offset from the top
				// L = the image offset from the left
				//
				// CACULATE THE TOP OFSET OF THE IMAGE WITHIN THE THUMB (T)
				//
				//   ( y * H / h ) - y = T
				//
				// CACULATE THE LEFT OFSET OF THE IMAGE WITHIN THE THUMB (L)
				//
				//   ( x * W / w ) - x = L
				//
				// ~ Robet Hurst

				var imageOffset = {
					'top': -Math.round(( cursor.y * image.height / thumb.height ) - cursor.y),
					'left': -Math.round(( cursor.x * image.width / thumb.width ) - cursor.x)
				}

				domElement.children('div.image').css({
					'top': imageOffset.top,
					'left': imageOffset.left
				});

			});

		}

		//define the constructor
		root.construct = function() {

			//bind all of the event handlers
			root.thumbHandler();
			root.positionZoom();
		}

		//self execute
		root.construct();

	}

	new thumbLogic;

});
