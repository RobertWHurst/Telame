$(function(){
	var createAlbumLogic = function(){

		//save this as root
		var root = this;

		//save selectors
		root.controls = $('#album_controls');
		root.createLink = $('a.create_album', root.controls);

		//create link handler
		root.createLinkClickHandler = function(){

			root.createLink.click(function(event){

				//stop the browser from following the link
				event.preventDefault();

				//capture the dom element
				var domElement = $(this);

				//get the modal
				var ajaxUrl = domElement.attr('href');
				$.get(core.domain + ajaxUrl, function(data){

					$('body').append(data);
					//bind new selectors
					root.createWrap = $('#create_album_wrap');
					root.create = $('div.create_album', root.createWrap);
					root.createForm = $('form', root.create);
					root.labels = $('label', root.createForm);
					root.titleInputWrap = $('div.titleInput', root.create);
					root.titleInput = $('input:text', root.create);
					root.titleLabel = $('label', root.titleInputWrap);
					root.descriptTextareaWrap = $('div.descriptTextarea', root.create);
					root.descriptTextarea = $('textarea', root.create);
					root.descriptLabel = $('label', root.descriptTextareaWrap);

					//execute the applicable handlers
					root.inputHoverHandler();
					root.inputFocusHandler();
					root.submitHandler();

					//popup the add new album form
					root.createWrap.fadeIn(300);
					//reveal the labels
					root.labels.show();
				});

				//pull up the modal screen
				core.showModalScreen(null, function(event){

					//on click of the screen hide the create form
					root.createWrap.fadeOut(300);

				}, {'speed': 300, 'animation': 'fade'});
			});

		};

		//input hover handler
		root.inputHoverHandler = function(){

			//input
			root.titleInput.hover(function(){

				//remove the old active class and add the inactive class
				root.create.addClass('active').addClass('hover');
				root.titleInputWrap.addClass('active').addClass('hover');

			},
			function(){

				//remove the old active class and add the inactive class
				root.create.removeClass('hover');
				root.titleInputWrap.removeClass('hover');
				if(root.titleInputWrap.hasClass('focus') === false){
					root.create.removeClass('active');
					root.titleInputWrap.removeClass('active');
				}

			});

			//textarea
			root.descriptTextarea.hover(function(){

				//remove the old active class and add the inactive class
				root.create.addClass('active').addClass('hover');
				root.descriptTextareaWrap.addClass('active').addClass('hover');

			},
			function(){

				//remove the old active class and add the inactive class
				root.create.removeClass('hover');
				root.descriptTextareaWrap.removeClass('hover');
				if(root.descriptTextareaWrap.hasClass('focus') === false){
					root.create.removeClass('active');
					root.descriptTextareaWrap.removeClass('active');
				}

			});
		};

		//input focus handler
		root.inputFocusHandler = function(){
			//input
			root.titleInput.focus(function(){

				//remove the old active class and add the inactive class
				root.create.addClass('active').addClass('focus');
				root.titleInputWrap.addClass('active').addClass('focus');
				root.descriptTextareaWrap.addClass('active').addClass('focus');

				//hide the label
				root.titleLabel.hide();

			});
			root.titleInput.blur(function(){

				//remove the old active class and add the inactive class
				root.create.removeClass('focus');
				root.titleInputWrap.removeClass('focus');
				root.descriptTextareaWrap.removeClass('focus');
				if(root.titleInputWrap.hasClass('hover') === false){
					root.descriptTextareaWrap.removeClass('active');
					root.titleInputWrap.removeClass('active');
					root.create.removeClass('active');
				}
				if($(this).val() === ''){
					//show the label
					root.titleLabel.show();
				}
			});

			//textarea
			root.descriptTextarea.focus(function(){

				//remove the old active class and add the inactive class
				root.create.addClass('active').addClass('focus');
				root.titleInputWrap.addClass('active').addClass('focus');
				root.descriptTextareaWrap.addClass('active').addClass('focus');

				//hide the label
				root.descriptLabel.hide();

			});
			root.descriptTextarea.blur(function(){

				//remove the old active class and add the inactive class
				root.create.removeClass('focus');
				root.titleInputWrap.removeClass('focus');
				root.descriptTextareaWrap.removeClass('focus');
				if(root.titleInputWrap.hasClass('hover') === false){
					root.descriptTextareaWrap.removeClass('active');
					root.titleInputWrap.removeClass('active');
					root.create.removeClass('active');
				}
				if($(this).val() === ''){
					//hide the label
					root.descriptLabel.show();
				}

			});

		};

		root.submitHandler = function(){

			//on submit
			root.createForm.submit(function(){

				//turn the input into a posting dialog
				inner.children().hide();
				inner.append('<p class="proccess">Adding New Album...</p>');

			});
		};

		//create construct
		root.construct = function(){
			$('a.js').show();

			root.createLinkClickHandler();

		};

		//self init
		root.construct();
	};

	new createAlbumLogic;
});