$(document).ready(function(){
	
	var wall_posts_logic = function(){
		
		//save this as root
		var root = this;
		
		//save the dom elements
		root.wallPostsWrapper = $('#profile_wall_posts', '#page_body');
		root.wallPosts = $('#profile_wall_posts > div.wall_post');
		root.wallInput = $('#WallPostPost', '#profile_wall_input');
		root.wallInputLabel = $('label', '#profile_wall_input');
		root.wallInputWrap = $('#profile_wall_input', '#profile_wall');	
		root.morePosts = $('div.more a', '#profile_wall');		
		
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
		
		//post hover handler
		root.postHoverHandler = function(){
			
			//hide all of the wall post controls
			root.wallPosts.children('div.delete, div.wall_to_wall').hide();
		
			//on hover event for each post	
			root.wallPostsWrapper.delegate('div.wall_post', 'hover', function(event){
			
				//grab the dom element
				domElement = $(this);
			
  				if(event.type == 'mouseover'){
			
					//get the target
					domElement = $(this);
			
					domElement.addClass('hover').children('div.delete, div.wall_to_wall').fadeIn(root.speed);
  				}
  				else{
				
					domElement.removeClass('hover').children('div.delete, div.wall_to_wall').fadeOut(root.speed);
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
				
				//hide the content
				domElement.children().hide();
				
				//add a proccess dialog
				domElement.append('<p class="proccess">deleting post...<p>');
			
				//send the ajax request
				$.post(core.domain + ajaxUrl, function(data){
					
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
				
			});

		}
		
		root.morePostsHandler = function(){
		
			root.morePosts.live('click', function(event){
				
				//prevent the default action
				event.preventDefault();
				
				//get the button
				var button = $(this);
				
				var offset = $('div.wall_post', '#profile_wall_posts').size();
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
					
						root.wallPostsWrapper.append(data);
						
						//remove and reapend the more posts button
						domElement.remove();
						
						//reappend the more posts button
						root.wallPostsWrapper.append(domElement);
						domElement.children('p.proccess').remove();
						domElement.children().fadeIn(root.speed * 3);	
					}
					else{		
						//restore the post
						domElement.remove();				
					}
				});
			});		
		}
		
		root.construct = function(){
		
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