$(function() {

	var wallPostsLogic = function() {

		//save this as root
		var root = this;

		//save the dom elements
		root.wallPostsWrapper = $('#profile_wall_posts', '#page_body');
		root.wallPostsComments = $('div.comments', root.wallPostsWrapper);
		root.commentForms = $('form', root.wallPostsComments);

		//post hover handler
		root.postHoverHandler = function() {

			//on hover event for each post	
			root.wallPostsWrapper.delegate('div.wall_post', 'hover', function(event) {

				//grab the dom element and its components
				var domElement = $(this);
				var baselineInfo = $('div.baseline_info', domElement);
				var controls = $('div.deletePost, div.wall_to_wall, div.baseline_controls', domElement);

				if (event.type == 'mouseover') {

					//hide the baseline info
					baselineInfo.hide(1, function() {

						//add the hover class to the post and fade in the controls
						domElement.addClass('hover');
						controls.show();
					});
				}
				else {

					//remove the hover class from the post and fade out the controls			
					domElement.removeClass('hover');
					controls.hide(1, function() {

						//show the baseline info
						baselineInfo.show();
					});
				}
			});

			//on hover event for each action	
			root.wallPostsWrapper.delegate('div.action', 'hover', function(event) {

				//grab the dom element and its components
				var domElement = $(this);
				var deleteControl = $('div.deletePost', domElement);

				if (event.type == 'mouseover') {

					//add the hover class to the post and fade in the controls
					domElement.addClass('hover');
					deleteControl.show();
				}
				else {

					//remove the hover class from the post and fade out the controls			
					domElement.removeClass('hover');
					deleteControl.hide();
				}
			});

			//on hover event for each post comment
			root.wallPostsWrapper.delegate('div.comment', 'hover', function(event) {

				//grab the dom element and its components
				var domElement = $(this);
				var deleteControl = $('div.deleteComment', domElement);

				if (event.type == 'mouseover') {

					//add the hover class to the comment and fade in the delete button
					domElement.addClass('hover');
					deleteControl.show();
				}
				else {

					//remove the hover class from the comment and fade out the delete button	
					domElement.removeClass('hover');
					deleteControl.hide();
				}
			});
		}

		//delete handler
		root.postDeleteHandler = function() {

			root.wallPostsWrapper.delegate('div.deletePost', 'click', function(event) {

				//prevent the default action
				event.preventDefault();

				//get the button
				var button = $(this);

				//get the ajaxUrl
				var ajaxUrl = $('a', button).attr('href');

				//get the target post (not the button)
				domElement = button.parent();

				//send the ajax request
				$.post(core.domain + ajaxUrl, function(data) {
					if (data === 'true') {
						flash.setMessage('info', 'The post was deleted.');
					}
					else {
						flash.setMessage('error', 'The post could not be deleted.');
					}
				});

				//slide up the post
				domElement.parent().slideUp(300, function() {
					$(this).remove();
				});

			});

		}

		//comments hover handler
		root.postCommentsHoverHandler = function() {

			//on hover event for each post comment
			root.wallPostsWrapper.delegate('a.showComments', 'click', function(event) {

				//prevent the default action
				event.preventDefault();

				//grab the dom element and its components
				var domElement = $(this);
				var wallCommentsWrap = $('div.commentsWrap', domElement.parents('div.wall_post_wrap'));

				domElement.remove();
				wallCommentsWrap.slideDown(300);
			});
		}

		root.postCommentDeleteHandler = function() {

			root.wallPostsWrapper.delegate('div.deleteComment', 'click', function(event) {

				//prevent the default action
				event.preventDefault();

				//get the button
				var domElement = $(this);

				//get the ajaxUrl
				var ajaxUrl = $('a', domElement).attr('href');

				//send the ajax request
				$.post(core.domain + ajaxUrl, function(data) {

					if (data === 'true') {
						flash.setMessage('info', 'The comment was deleted.');
					}
					else {
						flash.setMessage('error', 'The comment could not be deleted.');
					}
				});

				//slide up the post
				domElement.parent().slideUp(300, function() {
					$(this).remove();
				});

			});

		}

		root.postCommentInputHoverHandler = function() {

			root.wallPostsWrapper.delegate('div.commentInput', 'hover', function(event) {

				var domElement = $(this);

				if (event.type == 'mouseover') {
					//remove the old active class and add the inactive class
					domElement.addClass('active').addClass('hover');
				}
				else {
					//remove the old active class and add the inactive class
					domElement.removeClass('hover');
					if (domElement.hasClass('focus') === false) {
						domElement.removeClass('active');
					}
				}

			});

		}

		root.postCommentInputFocusHandler = function() {

			root.wallPostsWrapper.delegate('div.commentInput', 'focus', function() {

				var domElement = $(this);

				var input = $('textarea, input:text', domElement);

				if (!input.hasClass('textarea')) {

					//get the input id, and name.
					var inputMeta = {
						'id': input.attr('id'),
						'name': input.attr('name')
					}

					//convert the input to a textarea					
					input.replaceWith('<textarea id="' + inputMeta.id + '" class="textarea" name="' + inputMeta.name + '"></textarea>');

					//if the element selector					
					input = $('#' + inputMeta.id, domElement);

					//regain focus
					input.autogrow().focus();
				}
				else {

					//remove the old active class and add the inactive class
					domElement.addClass('active').addClass('focus');

					//hide the label
					$('label', domElement).hide();

				}


			});

			root.wallPostsWrapper.delegate('div.commentInput', 'blur', function() {

				var domElement = $(this);

				var input = $('textarea, input:text', domElement);

				//remove the old active class and add the inactive class
				domElement.removeClass('focus');
				if (domElement.hasClass('hover') === false) {
					domElement.removeClass('active');
				}

				if (input.val() === '') {
					$('label', domElement).show();

					if (input.hasClass('textarea')) {

						//get the input id, and name.
						var inputMeta = {
							'id': input.attr('id'),
							'name': input.attr('name')
						}

						//convert the textarea to an input
						input.replaceWith('<input type="text" id="' + inputMeta.id + '" name="' + inputMeta.name + '" value=""/>');

						//delete any textarea shadows
						$('div.autogrow_shadow').remove();

					}
				}

			});

		}

		root.postCommentSubmitHandler = function() {

			//on submit
			root.commentForms.submit(function(event) {


				//stop the form from sending the headers
				event.preventDefault();

				//save the current domElement
				var domElement = $(this);

				//get the action url
				var ajaxUrl = domElement.attr('action');

				//get the form data
				var formData = domElement.serialize();

				//get the input wrap
				var inputWrapper = domElement.parent();

				//get the input wrap
				var postComments = inputWrapper.parent();

				//get the input 
				var input = $('input:text, textarea', domElement);

				//remove any state classes from the input wrapper
				inputWrapper.removeClass('active hover focus');

				//clear the input
				input.attr('value', "").blur();

				//turn the input into a posting dialog
				inputWrapper.children().hide();
				inputWrapper.append('<p class="process">Posting...</p>');

				$.post(core.domain + ajaxUrl, formData, function(data) {

					flash.setMessage('info new_post', 'Your comment was posted.');

					if (data !== 'false') {


						//convert the data into a jquery object
						var data = $(data);

						//hide the controls						
						$('div.deleteComment', data).hide();

						//take the data and add it to the top of the wall
						inputWrapper.before(data);

						if (input.hasClass('textarea')) {

							//get the input id, and name.
							var inputMeta = {
								'id': input.attr('id'),
								'name': input.attr('name')
							}

							//convert the textarea to an input
							input.replaceWith('<input type="text" id="' + inputMeta.id + '" name="' + inputMeta.name + '" value=""/>');

							//delete any textarea shadows
							$('div.autogrow_shadow').remove();
						}

					}

					//remove the progress dialog and show the input box again
					inputWrapper.children('p.process').remove();
					inputWrapper.children().show();

				});

			});
		}

		root.likenessPostHandeler = function() {

			root.wallPostsWrapper.delegate('a.like, a.dislike', 'click', function(event) {

				//disable the default action
				event.preventDefault();

				var domElement = $(this);

				//get the ajax url
				var ajaxUrl = domElement.attr('href');

				$.post(core.domain + ajaxUrl, function() {

					if (domElement.hasClass('like')) {
						flash.setMessage('info', 'Post Liked');
					}
					else if (domElement.hasClass('dislike')) {
						flash.setMessage('info', 'Post Disliked');
					}

				});

			});
		}

		root.morePostsHandler = function() {

			root.wallPostsWrapper.delegate('a.more', 'click', function(event) {

				//prevent the default action
				event.preventDefault();

				//get the button
				var domElement = $(this);

				//get the current element count
				var offset = $('div.wall_post_wrap', '#profile_wall_posts').size();

				//get the ajaxUrl
				var ajaxUrl = $(domElement).attr('href') + '/' + offset;

				//hide the content
				domElement.hide();

				//add a process dialog
				domElement.parent().append('<p class="process">Loading more posts...<p>');

				//send the ajax request
				$.post(core.domain + ajaxUrl, function(data) {

					if (data !== 'false') {

						//mark all existing posts so they don't get animated in
						$('div.wall_post_wrap').addClass('na');

						//append the new page data
						domElement.parent().before(data);

						//hide the new wallposts
						$('div.wall_post_wrap').not('div.na').hide();

						//hide all of the wall post controls
						$('div.deletePost, div.deleteComment, div.wall_to_wall, div.baseline_controls').hide();
						$('div.baseline_info, div.commentInput label').show();

						//hide all of the comment wrappers with no comments
						$('div.comments').each(function() {
							var domElement = $(this);
							wallCommentsWrap = domElement.parents('div.commentsWrap');
							if (domElement.children().size() <= 1) {
								wallCommentsWrap.hide();
							}
							else {
								$('a.showComments', domElement.parents('div.wall_post_wrap')).remove();
							}
						});

						//remove the animation blocker class and animate in the new posts
						$('div.wall_post_wrap').removeClass('na').slideDown(600);

						//remove the process dialogue
						domElement.siblings('p.process').remove();

						//show the button again
						domElement.show();
					}
					else {

						//remove the process dialogue
						domElement.siblings('p.process').remove();

						//show the button again
						domElement.show();
					}
				});
			});
		}

		root.construct = function() {

			//hide all of the wall post controls
			$('div.deletePost, div.deleteComment, div.wall_to_wall, div.baseline_controls').hide();
			$('div.baseline_info, div.commentInput label').show();

			//hide all of the comment wrappers with no comments
			$('div.comments').each(function() {
				var domElement = $(this);
				wallCommentsWrap = domElement.parents('div.commentsWrap');
				if (domElement.children().size() <= 1) {
					wallCommentsWrap.hide();
				}
				else {
					$('a.showComments', domElement.parents('div.wall_post_wrap')).remove();
				}
			});

			//on hover event for each post	
			root.postHoverHandler();

			//on hover of comments
			root.postCommentsHoverHandler();

			//on the click of each delete button of each comment
			root.postCommentDeleteHandler();

			//on hover of comment inputs
			root.postCommentInputHoverHandler();

			//on focus of comment inputs
			root.postCommentInputFocusHandler();

			//on submit of a comment
			root.postCommentSubmitHandler();

			//on disliking or liking a post
			//root.likenessPostHandeler();

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