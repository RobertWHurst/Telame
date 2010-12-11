$(function() {

	//grab the wall post container
	var wallPostContainer = $( '#wall_posts' ),

	//set a speed for animation
		speed = 300,

	//set a cut off height for posts
		cutoff = 200;



	//INIT

	function init(){
		//hide post controls
		$( 'div.delete_post, div.baseline_controls', wallPostContainer ).hide();

		//hide the comment box if there are no comments, likes, or dislikes
		$( 'div.commentsWrap', wallPostContainer ).each(function(){

			//get the comments container
			var commentsContainter = $( this ),

			//get the comments
				comments = $( 'div.comment', commentsContainter ),

			//get the likes container
				likeness = $( 'div.likeness', commentsContainter );

			if( comments.length === 0 && likeness.length === 0 ){
				commentsContainter.hide();
			}

		});

		//truncate long posts

		//grab the content divs
		var contentDivs = $( 'div.post div.content', wallPostContainer );

		//loop through each content div
		contentDivs.each(function(){

			//grab the current content div
			var contentDiv = $( this ),

			//grab height of the div
				height = contentDiv.height(),

			//calculate the difference in height and cutoff
				difference = height - cutoff;

			//if the height exceeds 250px and it has not be truncated already then truncate it
			if( ! contentDiv.hasClass( 'truncated' ) && height > cutoff && difference > 50 ){
				contentDiv.height( 250 ).addClass( 'truncated' );

				//add a read more link
				contentDiv.append( '<a href="#" class="expand">Read More...</a>' );

				//grab the link
				var expLink = $( 'a.expand', contentDiv );

				//position the expLink
				contentDiv.css( 'position', 'relative' );
				expLink.css({
					'position': 'absolute',
					'bottom': 0,
					'left': 0
				});

				//setup a click event for the read more link
				expLink.one( 'click', function( e ){

					//prevent the hash link from scrolling the page
					e.preventDefault();

					//animate the div back to full height
					contentDiv.animate({ 'height': height }, speed ).css( 'height', 'auto' );

					//remove the expand link
					expLink.remove();
				});

			}
		});
	}

	//make an api for other scripts to clean posts
	wallPosts = {
		'clean': function(){
			init();
		}
	};

	//execute the init
	init();

	//HOVER

	//setup a mouseenter event for each post
	wallPostContainer.delegate( 'div.inner_post', 'mouseenter', function(){

		//fade in the controls
		$( 'div.delete_post, div.baseline_controls', this ).fadeIn( speed );

	});

	//setup a mouseleave event for each post
	wallPostContainer.delegate( 'div.inner_post', 'mouseleave', function(){

		//fade in the controls
		$( 'div.delete_post, div.baseline_controls', this ).fadeOut( speed );

	});


	
	//DELETE

	//setup a mouseleave event for each post
	wallPostContainer.delegate( 'div.delete_post', 'click', function( e ){
		e.preventDefault();

		//get the post
		var post = $( this ).parents( 'div.post' ),

		//get the delete url
			url = $( 'a', this ).attr( 'href' );

		//hide the post
		post.slideUp( speed );

		//ask the server to delete the post
		$.get( core.domain + url, {}, function( response ){

			if( response.match( 'true' ) ){
				flash.setMessage( 'info', 'Its gone.' );
				post.remove();
			}

			else {

				if( response.match( 'false' ) ){
					flash.setMessage( 'warning', 'Sorry, your not allowed to delete that post' );
				} else {
					flash.setMessage( 'error', 'An unknown error occurred, please try again' );
				}

				post.slideDown( speed );

			}

		});

	});

	//COMMENT

	//setup an event handler for the show comments button
	wallPostContainer.delegate( 'a.showComments', 'click', function( e ){
		e.preventDefault();

		//grab the comments container
		var commentsContainer = $( 'div.commentsWrap', $( this ).parents( 'div.post' ) );

		//slide open the comments container
		commentsContainer.slideDown( speed, function(){

			//add focus to the comment input
			$( 'input:text', commentsContainer ).focus();

		});

	});


	//LIKE AND DISLIKE

	//bind a click event to the like button
	wallPostContainer.delegate( 'a.like, a.dislike', 'click', function( e ) {

		//prevent a redirect
		e.preventDefault();

		//grab the link
		var link = $(this),

		//get the href from the link
			url = link.attr('href');

		if( link.hasClass('like') ){
			//update the like button
			if ( ! link.hasClass('liked')) {
				link.html('Unlike').addClass('liked');
			} else {
				link.html('Like').removeClass('liked');
			}
		}

		if( link.hasClass('dislike') ){
			//update the dislike button
			if ( ! link.hasClass('disliked')) {
				link.html('Undislike').addClass('disliked');
			} else {
				link.html('Dislike').removeClass('disliked');
			}
		}

		//ask the server to update its data
		$.get(core.domain + url, {}, function(data) {
			if (data !== 'false') {
				flash.setMessage('warning', 'Fixme: The action didn\'t pass me any ajax data. For now refresh to see the update');
			} else {
				flash.setMessage('error', 'Something went wrong. Try again');
			}
		});

	});

});