$(function(){
	
	var newsLogic = function(){
		
		//save this as root
		var root = this;
		
		//save the dom elements
		root.wallPostsWrapper = $('#news_feed', '#page_body');
		root.wallPosts = $('div.wallPost', '#news_feed');
		root.wallComments = $('div.comment', '#news_feed');
		//root.morePosts = $('div.more a', '#profile_wall');
		
		
		/*
		root.morePostsHandler = function(){
		
			root.morePosts.live('click', function(event){
				
				//prevent the default action
				event.preventDefault();
				
				//get the button
				var button = $(this);
				
				var offset = $('div.wallPost', '#news_feed').size();
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
			
						//hide all of the wall post controls
						$('div.baseline_controls, div.commentsWrap', data).hide();
						$('div.baseline_info', data).show();
						
						//append the new page data	
						root.wallPostsWrapper.append(data);
						
						//reappend the more posts button
						root.wallPostsWrapper.append(domElement);
						domElement.children('p.proccess').remove();
						
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
		*/
		
		root.construct = function(){
			
			//hide all of the wall post controls
			$('div.baseline_controls').hide();
			$('div.baseline_info').show();
		
			//hide all of the comment wrappers with no comments
			$('div.comments', root.wallPostsWrapper).each(function(){
				var domElement = $(this);
				wallCommentsWrap = domElement.parents('div.commentsWrap');
				if(domElement.children().size() <= 0){
					wallCommentsWrap.hide();
				}
				else{
					$('a.showComments', domElement.parents('div.wallPostWrap')).remove();				
				}
			});
			
			//on click for more posts			
			//root.morePostsHandler();
		}
		
		//self execute
		root.construct();
				
	}
	new newsLogic;

});