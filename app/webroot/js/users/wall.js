$(function(){
	
	var wallPostsLogic = function(){
		
		//save this as root
		var root = this;
		
		//save the dom elements
		root.wallPostsWrapper = $('#profile_wall_posts', '#page_body');
		root.wallPosts = $('div.wallPost', '#profile_wall_posts');
		root.wallComments = $('div.comment', '#profile_wall_posts');
		root.wallInput = $('#WallPostPost', '#profile_wall_input');
		root.wallInputLabel = $('label', '#profile_wall_input');
		root.wallInputWrap = $('#profile_wall_input', '#profile_wall');	
		root.morePosts = $('div.more a', '#profile_wall');
				
		//post hover handler
		root.postHoverHandler = function(){
		
			//on hover event for each post	
			root.wallPostsWrapper.delegate('div.wallPost', 'hover', function(event){
			
				//grab the dom element and its components
				var domElement = $(this);
				var baselineInfo = $('div.baseline_info', domElement);
				var controls = $('div.deletePost, div.wall_to_wall, div.baseline_controls', domElement);
			
  				if(event.type == 'mouseover'){
					
					//hide the baseline info
					baselineInfo.hide(1, function(){
					
						//add the hover class to the post and fade in the controls
						domElement.addClass('hover');
						controls.fadeIn(100);
					});
  				}
  				else{  		
					
					//remove the hover class from the post and fade out the controls			
					domElement.removeClass('hover');
					controls.fadeOut(100, function(){
						
						//show the baseline info
						baselineInfo.fadeIn(300);
					});
  				}
			});
		
			//on hover event for each post comment
			root.wallPostsWrapper.delegate('div.comment', 'hover', function(event){
			
				//grab the dom element and its components
				var domElement = $(this);
				var deleteControl = $('div.deleteComment', domElement);
			
  				if(event.type == 'mouseover'){
  				
					//add the hover class to the comment and fade in the delete button
					domElement.addClass('hover');
					deleteControl.fadeIn(100);
  				}
  				else{  		
					
					//remove the hover class from the comment and fade out the delete button	
					domElement.removeClass('hover');
					deleteControl.fadeOut(100);
  				}
			});
		}
		
		
		//delete handler
		root.postCommentsHandler = function(){
		
			//hide all of the comment wrappers with no comments
			$('div.comments', root.wallPostsWrapper).each(function(){
				var domElement = $(this);
				wallCommentsWrap = domElement.parents('div.commentsWrap');
				if(domElement.children().size() <= 1){
					wallCommentsWrap.hide();
				}
				else{
					$('a.showComments', domElement.parents('div.wallPostWrap')).remove();				
				}
			})
		
			//on hover event for each post comment
			root.wallPostsWrapper.delegate('a.showComments', 'click', function(event){
				
				//prevent the default action
				event.preventDefault();
			
				//grab the dom element and its components
				var domElement = $(this);
				var wallCommentsWrap = $('div.commentsWrap', domElement.parents('div.wallPostWrap'));
			
  				if(wallCommentsWrap.hasClass('open')){
  				
					wallCommentsWrap.removeClass('open').slideUp(300);
  				}
  				else{
  				
					wallCommentsWrap.addClass('open').slideDown(300);
  				}
			});
		}
		
		//delete handler
		root.postDeleteHandler = function(){
			
			root.wallPostsWrapper.delegate('div.deletePost', 'click', function(event){
				
				//prevent the default action
				event.preventDefault();
				
				//get the button
				var button = $(this);
				
				//get the ajaxUrl
				var ajaxUrl = $('a', button).attr('href');
				
				//get the target post (not the button)
				domElement = button.parent();
			
				//send the ajax request
				$.post(core.domain + ajaxUrl, function(data){
					
					if(data === 'true'){					
						//slide up the post
						domElement.parent().slideUp(300, function(){
							$(this).remove();
						});
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
				$.post(core.domain + ajaxUrl, function(data){
					
					if(data !== 'false'){
						
						//convert data to a jquery object
						data = $(data);
						
						//hide the new posts
						//data.hide();
												
						//remove and reapend the more posts button
						domElement.remove();
			
						//hide all of the wall post controls
						//$('div.deletePost, div.deleteComment, div.wall_to_wall, div.baseline_controls, div.commentsWrap', data).hide();
						//$('div.baseline_info', data).show();
						
						//append the new page data	
						root.wallPostsWrapper.append(data);
						
						//reappend the more posts button
						root.wallPostsWrapper.append(domElement);
						domElement.children('p.proccess').remove();
						
						//animate in the new posts		
						$('div.wallPostWrap').slideDown(600);
						
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
			$('div.deletePost, div.deleteComment, div.wall_to_wall, div.baseline_controls').hide();
			$('div.baseline_info').show();
		
			//on hover event for each post	
			root.postHoverHandler();
			
			root.postCommentsHandler();
			
			//on click event for delete and wall to wall
			root.postDeleteHandler();
			
			//on click for more posts			
			root.morePostsHandler();
		}
		
		//self execute
		root.construct();
				
	}
	new wallPostsLogic;

});