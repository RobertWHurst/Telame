$(document).ready(function(){
	
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
	new wall_posts_logic;

});