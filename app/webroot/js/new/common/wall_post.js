$(function() {

	//grab the wall post container
	var wallPostContainer = $( '#wall_posts' ),

	//set a speed for animation
		speed = 300;



	//INIT

	//hide post controls
	$( 'div.delete_post, div.baseline_controls', wallPostContainer ).hide();

	//hide the comment box if there are no comments
	$( 'div.commentsWrap', wallPostContainer ).each(function(){

		//get the comments container
		var commentsContainter = $( this ),

		//get the comments
			comments = $( 'div.comment', commentsContainter );

		if( comments.length === 0 ){
			commentsContainter.hide();
		}

	});


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


});