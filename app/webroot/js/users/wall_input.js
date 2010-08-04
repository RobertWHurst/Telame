$(document).ready(function(){
	
	var wall_posts_logic = function(){
		
		//save this as root
		var root = this;
		
		//save the dom elements
		root.wallInput = $('#WallPostPost', '#profile_wall_input');
		root.wallInputLabel = $('label', '#profile_wall_input');
		root.wallInputWrap = $('#profile_wall_input', '#profile_wall');
		root.wallInputSubmit = $('input[type="submit"]', '#profile_wall_input');
		
		//the animation speed
		root.speed = 100;
				
		//input wrap hover handler
		root.inputHoverHandler = function(action){
			
			//check the state
			if(action === 'in'){
				
				//remove the old active class and add the inactive class
				root.wallInputWrap.addClass('active').addClass('hover');
				
			}
			else if(action === 'out'){
				
				//remove the old active class and add the inactive class
				root.wallInputWrap.removeClass('hover');			
				if(root.wallInputWrap.hasClass('focus') === false){			
					root.wallInputWrap.removeClass('active');
				}	
				
			}
				
		}
		//input focus handler
		root.inputFocusHandler = function(action){
			
			//check the state
			if(action === 'in'){
				
				//remove the old active class and add the inactive class
				root.wallInputWrap.addClass('active').addClass('focus');
				
				//hide the label
				root.wallInputLabel.hide();
				
			}
			else if(action === 'out'){
				
				//remove the old active class and add the inactive class
				root.wallInputWrap.removeClass('focus');			
				if(root.wallInputWrap.hasClass('hover') === false){			
					root.wallInputWrap.removeClass('active');
				}	
								
				//if the inupt or textarea is empty then hide the label
				if(root.wallInput.val() === ''){
					root.wallInputLabel.show();
				}
			}
				
		}
		
		root.postHandler = function(ajaxUrl, postContent){
			//remove the 'no posts' warning (if it exists)
			$('p.no_posts', '#profile_wall_posts').remove();
			
			$.post(core.domain + ajaxUrl);
		}
		
		root.construct = function(){
			
			//on input hover event
			root.wallInputWrap.hover(function(){
				root.inputHoverHandler('in');
			},
			function(){
				root.inputHoverHandler('out');
			});
			
			//on input focus event
			root.wallInput.focus(function(){
				root.inputFocusHandler('in');
			});
			root.wallInput.blur(function(){
				root.inputFocusHandler('out');
			});
			
			//on posting a wall post
			root.profile_wall_input
			
		}
		
		//self execute
		root.construct();
				
	}
	new wall_posts_logic;

});