$(function() {

	//grab the wall post container
	var wallPostContainer = $( '#wall_posts' ),

	//set a speed for animation
		speed = 300;



	//INIT

	//hide post controls
	$( 'div.delete_comment, div.baseline_controls', wallPostContainer ).hide();


	//HOVER

	//setup a mouseenter event for each comment
	wallPostContainer.delegate( 'div.comment', 'mouseenter', function(){

		//fade in the controls
		$( 'div.delete_comment, div.baseline_controls', this ).fadeIn( speed );

	});

	//setup a mouseleave event for each comment
	wallPostContainer.delegate( 'div.comment', 'mouseleave', function(){

		//fade in the controls
		$( 'div.delete_comment, div.baseline_controls', this ).fadeOut( speed );

	});



	//DELETE

	//setup a mouseleave event for each post
	wallPostContainer.delegate( 'div.delete_comment', 'click', function( e ){
		e.preventDefault();

		//get the comment
		var comment = $( this ).parents( 'div.comment' ),

		//get the delete url
			url = $( 'a', this ).attr( 'href' );

		//hide the post
		comment.slideUp( speed );

		//ask the server to delete the post
		$.get( core.domain + url, {}, function( response ){

			if( response.match( 'true' ) ){
				flash.setMessage( 'info', 'Its gone.' );
				comment.remove();
			}

			else {

				if( response.match( 'false' ) ){
					flash.setMessage( 'warning', 'Sorry, your not allowed to delete that post' );
				} else {
					flash.setMessage( 'error', 'An unknown error occurred, please try again' );
				}

				comment.slideDown( speed );

			}

		});

	});

});