$(function(){
	
	var wall_posts_logic = function(){
		
		//save this as root
		var root = this;
		
		//save the dom elements
		root.wallPosts = $('#profile_wall_posts', '#profile_wall');
		root.wallInput = $('#WallPostPost', '#profile_wall_input');
		root.wallInputLabel = $('label', '#profile_wall_input');
		root.wallInputWrap = $('#profile_wall_input', '#profile_wall');
		root.wallInputForm = $('form', '#profile_wall_input');
		
		//the animation speed
		root.speed = 100;
				
		//input wrap hover handler
		root.inputHoverHandler = function(){
		
			//on input hover event
			root.wallInputWrap.hover(function(){
				
				//remove the old active class and add the inactive class
				root.wallInputWrap.addClass('active').addClass('hover');
				
			},
			function(){
				
				//remove the old active class and add the inactive class
				root.wallInputWrap.removeClass('hover');			
				if(root.wallInputWrap.hasClass('focus') === false){			
					root.wallInputWrap.removeClass('active');
				}
				
			});
				
		};
		
		//input focus handler
		root.inputFocusHandler = function(){
		
			root.wallInput.live('focus', function(){
				
				if(!root.wallInput.hasClass('textarea')){
				
					//get the input id, and name.
					var inputMeta = {
						'id': root.wallInput.attr('id'),
						'name': root.wallInput.attr('name')
					};
				
					//convert the input to a textarea
					root.wallInput.replaceWith('<textarea id="' + inputMeta.id + '" class="textarea" name="' + inputMeta.name + '"></textarea>');
					
					//if the element selector					
					root.wallInput = $('#WallPostPost', '#profile_wall_input');
					
					//regain focus
					root.wallInput.autogrow().focus();
				}
				else{
				
					//remove the old active class and add the inactive class
					root.wallInputWrap.addClass('active').addClass('focus');
				
					//hide the label
					root.wallInputLabel.hide();
				
				}
				
			});
			
			root.wallInput.live('blur', function(){
				
				//remove the old active class and add the inactive class
				root.wallInputWrap.removeClass('focus');			
				if(root.wallInputWrap.hasClass('hover') === false){			
					root.wallInputWrap.removeClass('active');
				}	
								
				//if the input or textarea is empty then hide the label
				if(root.wallInput.val() === ''){
					root.wallInputLabel.show();
					
					if(root.wallInput.hasClass('textarea')){
				
						//get the input id, and name.
						var inputMeta = {
							'id': root.wallInput.attr('id'),
							'name': root.wallInput.attr('name')
						};
				
						//convert the textarea to an input
						root.wallInput.replaceWith('<input type="text" id="' + inputMeta.id + '" name="' + inputMeta.name + '" value=""/>');
						
						//delete any textarea shadows
						$('div.autogrow_shadow').remove();
					
						//if the element selector					
						root.wallInput = $('#WallPostPost', '#profile_wall_input');
					}
				}
				
			});
				
		};
		
		root.submitHandler = function(){
			
			//on submit
			root.wallInputForm.submit(function(event){
			
				//stop the form from sending the headers
				event.preventDefault();
				
				//get the form data
				var formData = root.wallInputForm.serialize();
				
				//get the action url
				var ajaxUrl = root.wallInputForm.attr('action');
				
				//remove any state classes from the input wrapper
				root.wallInputWrap.removeClass('active hover focus');
				
				//save the inner box to a var
				var inner = $('div.inner', '#profile_wall_input');
				
				//clear the input
				root.wallInput.attr('value', "").blur();
					
				//turn the input into a posting dialog
				inner.children().hide();
				inner.append('<p class="proccess">Posting...</p>');
				
				$.post(core.domain + ajaxUrl, formData, function(data){
					
					if(data !== 'false'){
					
						flash.setMessage('info', 'Your wall post was posted.');
						
						//convert the data into a jquery object
						var data = $(data);
						
						//hide the controls						
						$('div.deletePost, div.deleteComment, div.wall_to_wall, div.baseline_controls, div.commentsWrap', data).hide();
						$('div.baseline_info', data).show();
						
						//take the data and add it to the top of the wall
						root.wallPosts.prepend(data);
						
						if(root.wallInput.hasClass('textarea')){
				
							//get the input id, and name.
							var inputMeta = {
								'id': root.wallInput.attr('id'),
								'name': root.wallInput.attr('name')
							}
				
							//convert the textarea to an input
							root.wallInput.replaceWith('<input type="text" id="' + inputMeta.id + '" name="' + inputMeta.name + '" value=""/>');
						
							//delete any textarea shadows
							$('div.autogrow_shadow').remove();
					
							//if the element selector					
							root.wallInput = $('#WallPostPost', '#profile_wall_input');
						}
						
					}
						
					//remove the progress dialog and show the input box again
					inner.children('p.proccess').remove();
					inner.children().show();
					
				});
				
			});
			
		};
		
		root.construct = function(){
		
			//show the label
			root.wallInputLabel.show();
			
			//on input hover event
			root.inputHoverHandler();
			
			//on input focus event
			root.inputFocusHandler();
			
			//on submit handleer
			root.submitHandler();
			
		};
		
		//self execute
		root.construct();
				
	}
	new wall_posts_logic;

});