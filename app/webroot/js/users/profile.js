$(document).ready(function(){

	var wall_input_logic = function(){
		
		//save this as root
		var root = this;
		
		//save the dom elements
		root.wallInput = $('#WallPostPost', '#profile_wall_input');
		root.wallInputLabel = $('label', '#profile_wall_input');
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
		
		//define the constructor
		root.construct = function(){
			
			//on hover event
			root.wallInputWrap.live('hover', function(){
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
		
	}
	
	var wall_posts_logic = function(){
		
		//save this as root
		var root = this;
		
		//save the dom elements
		root.wallPosts = $('div.wall_post', '#profile_wall_posts');
		
		//the animation speed
		root.speed = 100;
		
		//the domain
		root.domain = 'http://telame.com';
		
		//hover handler
		root.hoverHandler = function(domElement, action){
			
			//if fading in
			if(action === 'in'){
				domElement.addClass('hover').children('div.delete, div.wall_to_wall').fadeIn(root.speed);
			}
			//if fading out
			else if(action === 'out'){
				domElement.removeClass('hover').children('div.delete, div.wall_to_wall').fadeOut(root.speed);
			}
			
		}
		
		//delete handler
		root.deleteHandler = function(domElement, ajaxUrl){
			
			//hide the content
			domElement.children().hide();
			
			//add a proccess dialog
			domElement.append('<p class="proccess">deleting post...<p>');
			
			//send the ajax request
			$.get(root.domain + ajaxUrl, function(data){
				
				if(data === 'true'){					
					//slide up the post
					domElement.fadeOut(root.speed * 3, function(){
						domElement.remove();
					});
				}
				else{		
					//restore the post
					domElement.children('p.proccess').remove();
					domElement.children().fadeIn(root.speed * 3);					
				}
			});
		}
		
		root.construct = function(){
		
			//hide all of the wall post controls
			root.wallPosts.children('div.delete, div.wall_to_wall').hide();
			
			//on hover event for each post
			root.wallPosts.hover(function(){
				
				//get the target
				domElement = $(this);
				
				root.hoverHandler(domElement, 'in');
			},
			function(){
				
				domElement = $(this);
				
				root.hoverHandler(domElement, 'out');
			});
			
			//on click event for delete
			root.wallPosts.children('div.delete').click(function(event){
				
				//prevent the default action
				event.preventDefault();
				
				//get the button
				var button = $(this);
				
				//get the ajaxUrl
				var ajaxUrl = '/jx' + $('a', button).attr('href');
				
				//get the target post (not the button)
				domElement = button.parent();
				
				root.deleteHandler(domElement, ajaxUrl);
			});
		}
		
		//self execute
		root.construct();
				
	}
	
	new wall_input_logic;
	new wall_posts_logic;

});