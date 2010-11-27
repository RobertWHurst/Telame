$(function(){

	//grab the wall post container
	var wallPostContainer = $( '#wall_posts' ),

	//grab the 'more posts' button
		moreButton = $( '#more_posts' ),

	//grab its url too
		moreButtonUrl = $( 'a', moreButton ).attr( 'href' ),

	//define a speed for animation
		speed = 300;



	//INIT

	//define the init function

	(function init(){

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
							wall.addPost( this, true );

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



	//WALL
	wall = {

		//define a function for adding posts
		'addPost': function( data, appendData ){

			if( ! appendData ){

				//prepend the post
				wallPostContainer.prepend( data );

			} else {

				//add the post before the 'more posts' button
				$( '#more_posts' ).before( data );

			}

			//grab the new post
			var wallPost = $( '#' + $( data ).attr( 'id' ) );

			//clear any values the browser may throw into the comment input
			$( 'input:text', wallPost ).val( '' );

			//hide the comments container
			$( 'div.commentsWrap', wallPost ).hide();

			//hide the wall post and slide it in
			wallPost.hide().slideDown( speed );

		},

		//define a function for adding posts
		'addComment': function( parentId, data ){

			//grab the comments container
			var commentsContainer = $( '#' + parentId ),
			
			//grab the comment input
				commentInput = $( 'div.comment_input', commentsContainer );

			//append the comment before the input
			commentInput.before( data );

		}
	}

});