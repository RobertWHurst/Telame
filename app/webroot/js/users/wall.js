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
		
		//the domain
		root.domain = 'http://' + window.location.hostname;
				
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
		root.postHoverHandler = function(domElement, action){
			
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
		root.postDeleteHandler = function(domElement, ajaxUrl){
			
			//hide the content
			domElement.children().hide();
			
			//add a proccess dialog
			domElement.append('<p class="proccess">deleting post...<p>');
			
			//send the ajax request
			$.post(root.domain + ajaxUrl, function(data){
				
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
		
		root.morePostsHandler = function(domElement, ajaxUrl){
			
			//hide the content
			domElement.children().hide();
			
			//add a proccess dialog
			domElement.append('<p class="proccess">Loading more posts...<p>');
			
			//send the ajax request
			$.post(root.domain + ajaxUrl, {
				'data[offset]': $('div.wall_post', '#profile_wall_posts').size()
			}, 
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
		}
		
		root.construct = function(){
		
			//hide all of the wall post controls
			root.wallPosts.children('div.delete, div.wall_to_wall').hide();
			
			//on hover event for each post	
			root.wallPostsWrapper.delegate('div.wall_post', 'hover', function(event){
  				if(event.type == 'mouseover'){
				
					//get the target
					domElement = $(this);
				
					root.postHoverHandler(domElement, 'in');
  				}
  				else{
				
					domElement = $(this);
				
					root.postHoverHandler(domElement, 'out');
  				}
			});
			
			//on click event for delete and wall to wall
			root.wallPostsWrapper.delegate('div.delete, div.wall_to_wall', 'click', function(event){
				
				//prevent the default action
				event.preventDefault();
				
				//get the button
				var button = $(this);
				
				//get the ajaxUrl
				var ajaxUrl = '/jx' + $('a', button).attr('href');
				
				//get the target post (not the button)
				domElement = button.parent();
				
				root.postDeleteHandler(domElement, ajaxUrl);
			});
			
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
			
			//on click for more posts			
			root.morePosts.live('click', function(event){
				
				//prevent the default action
				event.preventDefault();
				
				//get the button
				var button = $(this);
				
				//get the ajaxUrl
				var ajaxUrl = '/jx' + $(button).attr('href');
				
				//get the target post (not the button)
				domElement = button.parent();
				
				root.morePostsHandler(domElement, ajaxUrl);
			});
		}
		
		//self execute
		root.construct();
				
	}
	new wall_posts_logic;

});