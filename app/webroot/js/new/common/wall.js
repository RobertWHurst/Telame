$(function(){

	//grab the 'more posts' button
	var moreButton = $( '#more_posts' ),

	//grab its url too
		moreButtonUrl = $( 'a', moreButton ).attr( 'href' ),

	//define a speed for animation
		speed = 300;

	

	//INIT

	//define the init function

	(function init(){

		//make sure the page has a more button
		if( ! $( '#more_posts' ).length ){
			return false;
		}

		//grab the total number of posts
		var postCount = $( 'div.post', '#wall_posts' ).length;

		//show the 'more posts' button if the server has more posts
		$.post( core.domain + moreButtonUrl + '/' + postCount + '/', {}, function( response ){

			//if the server passed us data then save it and reveal the button
			if( $( response ).is( 'div' ) ){
				moreButton.show();

				//CLICK

				//setup a click event to dump the data on
				moreButton.click(function( e ){

					//stop the browser from following the link via http
					e.preventDefault();

					//dump the new posts into the wall
					$( response ).each(function(){

						if( $( this ).hasClass( 'post' ) ){

							//add the post
							wallInput.addPost( this, true );

						}

					});

					//unbind the click event
					moreButton.unbind( 'click' );

					//hide the more button
					moreButton.slideUp( speed );

					//self execute
					init();

				});
				
			}

		});

	})();

});