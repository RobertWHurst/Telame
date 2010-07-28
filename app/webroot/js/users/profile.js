$(document).ready(function(){

	var wall_input_logic = function(){		
		
		//save this as root
		var root = this;
		
		//save the dom elements
		root.wallInput = $('#WallPostPost', '#profile_wall_input');
		root.wallInputWrap = $('#profile_wall_input', '#profile_wall');
		
		//the animation speed
		root.speed = 300;
		
		//input wrap hover handler
		root.hoverHandler = function(action){
			
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
		root.focusHandler = function(action){
			
			//check the state
			if(action === 'in'){
				
				//remove the old active class and add the inactive class
				root.wallInputWrap.addClass('active').addClass('focus');
				
			}
			else if(action === 'out'){
				
				//remove the old active class and add the inactive class
				root.wallInputWrap.removeClass('focus');			
				if(root.wallInputWrap.hasClass('hover') === false){			
					root.wallInputWrap.removeClass('active');
				}	
				
			}
				
		}
		
		//define the constructor
		root.construct = function(){
			
			//on hover event
			root.wallInputWrap.hover(function(){
				root.hoverHandler('in');
			},
			function(){
				root.hoverHandler('out');
			});
			
			//on focus event
			root.wallInput.focus(function(){
				root.focusHandler('in');
			});
			root.wallInput.blur(function(){
				root.focusHandler('out');
			});
			
		}
		
		//self execute
		root.construct();
		
	};
	
	new wall_input_logic;

});