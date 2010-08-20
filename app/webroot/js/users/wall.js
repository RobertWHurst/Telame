$(function(){
	
	var wall_posts_logic = function(){
		
		//save this as root
		var root = this;
		
		//save the dom elements
		root.wallPostsWrapper = $('#profile_wall_posts', '#page_body');
		root.wallPosts = $('div.wallPost', '#profile_wall_posts');
		root.wallInput = $('#WallPostPost', '#profile_wall_input');
		root.wallInputLabel = $('label', '#profile_wall_input');
		root.wallInputWrap = $('#profile_wall_input', '#profile_wall');	
		root.morePosts = $('div.more a', '#profile_wall');
				
		//post hover handler
		root.postHoverHandler = function(){
		
			//on hover event for each post	
			root.wallPostsWrapper.delegate('div.wallPost, div.comment', 'hover', function(event){
			
				//grab the dom element
				domElement = $(this);
			
  				if(event.type == 'mouseover'){
			
					//get the target
					domElement = $(this);
			
					domElement.addClass('hover').children('div.delete, div.wall_to_wall').fadeIn(100);
  				}
  				else{
				
					domElement.removeClass('hover').children('div.delete, div.wall_to_wall').fadeOut(100);
  				}
			});
		}
		
		//delete handler
		root.postDeleteHandler = function(){
			
			root.wallPostsWrapper.delegate('div.delete', 'click', function(event){
				
				//prevent the default action
				event.preventDefault();
				
				//get the button
				var button = $(this);
				
				//get the ajaxUrl
				var ajaxUrl = '/jx' + $('a', button).attr('href');
				
				//get the target post (not the button)
				domElement = button.parent();
			
				//send the ajax request
				$.post(core.domain + ajaxUrl, function(data){
					
					if(data === 'true'){					
						//slide up the post
						domElement.slideUp(300);
					}
				});
				
			});

		}
		
		root.morePostsHandler = function(){
		
			root.morePosts.live('click', function(event){
				
				//prevent the default action
				event.preventDefault();
				
				//get the button
				var button = $(this);
				
				var offset = $('div.wallPost', '#profile_wall_posts').size();
				//get the ajaxUrl
				var ajaxUrl = '/jx' + $(button).attr('href') + '/' + offset;

				//get the target post (not the button)
				domElement = button.parent();
				
				//hide the content
				domElement.children().hide();
				
				//add a proccess dialog
				domElement.append('<p class="proccess">Loading more posts...<p>');
				
				//send the ajax request
				$.post(core.domain + ajaxUrl, 
				function(data){
					
					if(data !== 'false'){
						
						//convert data to a jquery object
						data = $(data);
						
						//hide the new posts
						$(data).hide();
												
						//remove and reapend the more posts button
						domElement.remove();
						
						//append the new page data	
						root.wallPostsWrapper.append(data);
						
						//reappend the more posts button
						root.wallPostsWrapper.append(domElement);
						domElement.children('p.proccess').remove();
			
						//hide all of the wall post controls
						$('div.delete, div.wall_to_wall').hide();
						
						//animate in the new posts		
						$('div.wall_post').slideDown(600);
						
						//show the button again
						domElement.children().fadeIn(300);	
					}
					else{		
						//restore the post
						domElement.remove();				
					}
				});
			});		
		}
		
		root.construct = function(){
			
			//hide all of the wall post controls
			$('div.delete, div.wall_to_wall').hide();
		
			//on hover event for each post	
			root.postHoverHandler();
			
			//on click event for delete and wall to wall
			root.postDeleteHandler();
			
			//on click for more posts			
			root.morePostsHandler();
		}
		
		//self execute
		root.construct();
				
	}
	new wall_posts_logic;

});