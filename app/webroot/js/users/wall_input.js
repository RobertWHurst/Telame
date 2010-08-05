$(document).ready(function(){
	
	var wall_posts_logic = function(){
		
		//save this as root
		var root = this;
		
		//save the dom elements
		root.wallPosts = $('#profile_wall_posts', '#profile_wall')
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
				
		}
		
		//input focus handler
		root.inputFocusHandler = function(){
		
			root.wallInput.focus(function(){
				
				//remove the old active class and add the inactive class
				root.wallInputWrap.addClass('active').addClass('focus');
				
				//hide the label
				root.wallInputLabel.hide();
				
			});
			
			root.wallInput.blur(function(){
				
				//remove the old active class and add the inactive class
				root.wallInputWrap.removeClass('focus');			
				if(root.wallInputWrap.hasClass('hover') === false){			
					root.wallInputWrap.removeClass('active');
				}	
								
				//if the inupt or textarea is empty then hide the label
				if(root.wallInput.val() === ''){
					root.wallInputLabel.show();
				}
				
			});
				
		}
		
		root.sumbitHandler = function(){
			
			//on submit
			root.wallInputForm.submit(function(event){
			
				//stop the form from sending the headers
				event.preventDefault();
				
				//get the form data
				var formData = root.wallInputForm.serialize();
				
				//get the action url
				var ajaxUrl = '/jx' + root.wallInputForm.attr('action');
				
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
					
						
						//convert the data into a jquery object
						var data = $(data);
						
						//hide the controls
						data.children('div.delete, div.wall_to_wall').hide();
						
						//take the data and add it to the top of the wall
						root.wallPosts.prepend(data);
						
					}
					else{
						
						alert('Sorry but it seems that your browser is no longer able to connect to the server. To post to the wall a contention is required');
					
					}
						
					//remove the progress dialog and show the input box again
					inner.children('p.proccess').remove();
					inner.children().show();
					
				});
				
			});
			
		}
		
		root.construct = function(){
			
			//on input hover event
			root.inputHoverHandler();
			
			//on input focus event
			root.inputFocusHandler();
			
			//on submit handleer
			root.sumbitHandler();
			
		}
		
		//self execute
		root.construct();
				
	}
	new wall_posts_logic;

});