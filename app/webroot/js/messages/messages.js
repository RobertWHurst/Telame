$(function() {

	var messageLogic = function() {

		//save this as root
		var root = this;

		//save the dom elements
		root.messages = $('div.message', '#messages');
		root.readMessages = $('div.read', '#messages');
		root.unreadMessages = $('div.unread', '#messages');
		root.repyButtons = $('div.delete a', '#messages');
		root.messagesContainer = $('#messages', '#content');

		//the animation speed
		root.speed = 300;

		//post hover handler
		root.hoverHandler = function() {

			//hide all of the wall post controls
			root.messages.children('div.delete, div.reply').hide();

			//on hover event for each post	
			root.messagesContainer.delegate('div.message', 'hover', function(event) {

				//grab the dom element
				domElement = $(this);

				if (event.type == 'mouseover') {

					//get the target
					domElement = $(this);

					domElement.addClass('hover').children('div.delete, div.reply').fadeIn(100);
				}
				else {

					domElement.removeClass('hover').children('div.delete, div.reply').fadeOut(100);
				}
			});
		}

		//input delete handler
		root.deleteHandler = function() {

			root.messages.each(function() {

				var domElement = $('div.delete a', this);

				domElement.live('click', function(event) {

					//prevent the bowser from following the link
					event.preventDefault();

					//get the url of the button
					var ajaxUrl = domElement.attr('href');

					//tell the message controller to delete the message
					$.post(core.domain + ajaxUrl, {}, function(data) {
						if (data === 'true') {
							flash.setMessage('info', 'The message was deleted.');
						}
						else {
							flash.setMessage('error', 'The message could not be deleted.');
						}
					});

					//slide up the message
					domElement.parent().parent().slideUp(root.speed);

				});

			});

		}

		//define the constructor
		root.construct = function() {

			//animate in messages
			root.readMessages.hide().fadeIn(600);

			//bind all of the event handlers
			root.deleteHandler();
			root.hoverHandler();

		}

		//self execute
		root.construct();

	}

	new messageLogic;

});
