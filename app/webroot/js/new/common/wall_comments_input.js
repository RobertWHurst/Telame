$(function() {

	//grab the comment input divs
	var wallPostContainer = $( '#wall_posts' ),
		commentInputDivs = $( 'div.comment_input', wallPostContainer );

	//INIT

	commentInputDivs.each(function(){
		var inputDiv = $( this ),

		//grab the default text
			defaultText = $( 'label', inputDiv ).html();

		//fill the input with the default text from the label
		$( 'input:text', inputDiv ).val( defaultText );

	});



	//HOVER

	//setup a mouseenter event
	wallPostContainer.delegate( 'div.comment_input input:text, div.comment_input textarea', 'mouseenter', function(){
		var inputDiv = $( this ).parents( 'div.comment_input' );

		//show the green highlight by adding the active and hover class
		inputDiv.addClass( 'hover' ).addClass( 'active' );


	});

	//setup a mouseleave event
	wallPostContainer.delegate( 'div.comment_input input:text, div.comment_input textarea', 'mouseleave', function(){
		var inputDiv = $( this ).parents( 'div.comment_input' );

		//remove the hover class
		inputDiv.removeClass( 'hover' );

		//if the input is out of focus remove the active class as well
		if( ! inputDiv.hasClass( 'focus' ) ){
			inputDiv.removeClass( 'active' );
		}

	});

	//FOCUS

	//setup a focus event
	wallPostContainer.delegate( 'div.comment_input input:text, div.comment_input textarea', 'focus', function(){
		var input = $( this ),
			inputDiv = input.parents( 'div.comment_input' ),
			defaultText = $( 'label', inputDiv ).html();

		//show the green highlight by adding the active and hover class
		inputDiv.addClass( 'focus' ).addClass( 'active' );

		//clear the default text
		if( input.val() === defaultText ){
			input.val( '' );
		}

		if( input.is( 'input:text' ) ){

			//make the input a textarea after saving its attributes
			var inputId = input.attr( 'id' ),
				inputName = input.attr( 'name' ),
				inputVal = input.attr( 'value' );
			input.replaceWith( '<textarea id="' + inputId + '" name="' + inputName + '">' + inputVal + '</textarea>' );

			//grab the new textarea
			input = $( '#' + inputId );

			//apply the autogrow plugin and regain focus
			input.autogrow().focus();
		}

	});

	//setup a blur event
	wallPostContainer.delegate( 'div.comment_input input:text, div.comment_input textarea', 'blur', function(){
		var input = $( this ),
			inputDiv = input.parents( 'div.comment_input' ),
			defaultText = $( 'label', inputDiv ).html();

		//remove the hover class
		inputDiv.removeClass( 'focus' );

		//if the input is out of focus remove the active class as well
		if( ! inputDiv.hasClass( 'hover' ) ){
			inputDiv.removeClass( 'active' );
		}

		//the textarea must be empty before it will turn back into an input
		if( input.is( 'textarea' ) && input.val() === '' ){

			//make the input a textarea after saving its attributes
			var inputId = input.attr( 'id' ),
				inputName = input.attr( 'name' );
			input.replaceWith( '<input type="text" id="' + inputId + '" name="' + inputName + '">' );

			//grab the new textarea
			input = $( '#' + inputId );

			//delete any textarea shadows
			$('div.autogrow_shadow').remove();

			//restore the default text
			input.val( defaultText );
		}

	});

	//SUBMIT

	//setup an event for submitting the form
	wallPostContainer.delegate( 'div.commentsWrap form', 'submit', function( e ){
		var form = $( this ),
			commentsDivId = form.parents( 'div.post' ).attr( 'id' ),
			input = $( 'input:text', form );

		//stop the browser from submitting the form via http
		e.preventDefault();

		//grab and serialize the form data
		var data = form.serialize(),
			value = input.val(),

		//grab the action from the form
			action = form.attr( 'action' );

		//clear the input and re trigger the blur event to make sure everything is reset
		input.val( '' ).blur();

		//send the data to the server
		$.post( core.domain + action, data, function( response ){

			//when the server responds restore the input value on failure
			//and throw a message, or, on success just throw a message

			if( $( response ).is( 'div' ) ){
					flash.setMessage( 'info', 'Your post was made successfully' );

					//send the data to the wall script
					addComment( commentsDivId, response );
			}

			else {

				if( response.match( 'false' ) ){
					flash.setMessage( 'warning', 'Sorry you are not allowed to post here' );
				} else {
					flash.setMessage( 'error', 'An unknown error occurred, try again' );
				}

				//restore the input's value
				input.val( value ).focus();

			}

		});

	});


	//ADD COMMENT

	//define a function for adding posts
	function addComment( parentId, data ){

			//grab the comments container
			var commentsContainer = $( '#' + parentId ),

			//grab the comment input
				commentInput = $( 'div.comment_input', commentsContainer );

			//append the comment before the input
			commentInput.before( data );
	}

});