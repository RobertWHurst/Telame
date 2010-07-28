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
		root.hover = function(action){
			
			//the background images
			var bg = ["url('../img/profile/profile_wall_input.png')", "url('../img/profile/profile_wall_input_active.png')"]
			
			//check the state
			if(action === 'in'){
				
				//remove the old active class and add the inactive class
				root.wallInputWrap.removeClass('active').addClass('inactive');
				
				//animate in the origional state background
				root.wallInputWrap.animate({
					'background': bg.1
				}, root.speed);
				
			}
			else if(action === 'out'){
				
				//remove the old active class and add the inactive class
				root.wallInputWrap.removeClass('inactive').addClass('active');
				
				//animate in the active state background
				root.wallInputWrap.animate({
					'background': bg.2
				}, root.speed);				
				
			}
				
		}
		
		//define the constructor
		root.construct = function(){
			
			//on hover event
			root.wallInputWrap.hover(function(){
				root.hover('in');
			},
			function(){
				root.hover('out');
			});
			
		}
		
		//self execute
		root.construct();
		
	}
	
	new wall_input_logic;

});