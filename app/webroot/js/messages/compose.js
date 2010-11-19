$(function() {

	var messageLogic = function() {

		//save this as root
		var root = this;

		//save the dom elements
		root.messageComposerWrap = $('#create_message_wrap');
		root.messageComposerRecipientInputWrap = $('div.recipients', root.messageComposerWrap);
		root.messageComposerRecipientInput = $('#MessageUserSlugs', root.messageComposerRecipientInputWrap);

		//create data objects
		root.selectedUsers = {};

		//rebuild ui
		root.formatUi = function() {

			root.messageComposerRecipientInput
					.hide()
					.wrap('<div class="fake_input">')
					.before('<input id="full_name_input" type="text" />'),

				//bind to the new fake input
					root.fullNameInput = $('#full_name_input');
			root.fakeInput = $('div.fake_input');

			root.fullNameInput
					.css({
					         'background': 'transparent',
					         'border': 'none',
					         'padding': '2px'
					     }),

				//make sure that when the fake input is clicked focus is given to the real input
					root.fakeInput.click(function() {
						root.fullNameInput.focus();
					});

			//execute the input handler
			root.inputValueHandler();
		}

		//post hover handler
		root.inputValueHandler = function() {

			//REMOVE THIS
			root.messageComposerRecipientInputWrap.append('<div id="hintList">');
			root.hintlist = $('#hintList');

			//set the timer
			var timer = processing = string = i = the_slugs = '';

			root.fakeInput.delegate('div.user_name', 'click', function() {

				var domElement = $(this);
				root.inputValueHandler.removeUser(domElement.attr('id'));
				domElement.remove();

			});

			root.fullNameInput.keydown(function(event) {

				//check to see if the enter key was pressed
				var keycode = (event.keyCode ? event.keyCode : event.which);
				if (keycode == '13') {

					//prevent the enter key
					event.preventDefault();

					if (root.hintlist.length > 0) {
						$('div.row', root.hintlist).first().click();
					}

					return true;
				}

				//make sure to cancel any previous timers
				if (processing == true) {
					clearTimeout(timer);
					processing = false;
				}

				//define the function that requests hint data
				var requestHint = function() {

					//set the string
					string = domElement.val().replace(' ', '_');

					//string length must be 2 char or longer to ping the server	
					if (string.length > 1) {

						$.get(core.domain + '/m/a/' + string, function(data) {

							//clear the list
							root.hintlist.empty();

							if (data.length > 0) {

								for (i in data) {

									//create the row
									addrow(data[i]);

								}
							}
						});
					}
				}

				//define function to create a hint row
				var addrow = function(userData) {

					if (typeof userData !== 'object') {
						return false;
					}

					if (typeof root.selectedUsers[userData.slug] == 'undefined') {

						//build the row
						root.hintlist.append('<div class="row"><div id="' + userData.slug + '" class="user_name">' + userData.full_name + '</div><div class="email">' + userData.email + '</div></div>');

						//grab the rows
						$('div.row', '#hintList').click(function() {

							//save the dom element
							var domElement = $(this);

							//grab the slug
							var slug = domElement.children('div.user_name').attr('id');

							//create a new user block in the fake input
							root.fullNameInput.before(domElement.children('div.user_name'));

							//add the user slug to the data array
							root.inputValueHandler.addUser(slug);

							//remove the hint row
							domElement.remove();

							root.fullNameInput.focus();

						});
					}
				}

				root.inputValueHandler.addUser = function(slug) {

					//add the user slug to the selected array
					root.selectedUsers[slug] = slug;

					root.inputValueHandler.updateSlugsInput();
				}

				root.inputValueHandler.removeUser = function(slug) {

					//add the user slug to the selected array
					delete root.selectedUsers[slug];

					root.inputValueHandler.updateSlugsInput();

				}

				root.inputValueHandler.updateSlugsInput = function() {

					//clear the input;
					root.fullNameInput.val('');

					//clear the slugs input
					the_slugs = '';

					//add the slugs to the hidden slug input
					i = 1;
					for (k in root.selectedUsers) {

						if (root.selectedUsers.hasOwnProperty(k)) {
							the_slugs += root.selectedUsers[k];

							if (i < core.sizeOf(root.selectedUsers)) {
								the_slugs += ', ';
							}

							//adv the iderator
							i += 1;
						}
					}
					root.messageComposerRecipientInput.val(the_slugs);
				}

				// save the dom element
				var domElement = $(this);

				//clear the list
				root.hintlist.empty();

				//get the hints
				processing = true;
				timer = setTimeout(requestHint, 150);

			});

		}

		//define the constructor
		root.construct = function() {

			//bind all of the event handlers
			root.formatUi();

		}

		//self execute
		root.construct();

	}

	new messageLogic;

});