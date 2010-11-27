$(function() {

	//grab the wall input div
	var inputDiv = $( '#wall_input' ),

	//grab the wall input
		input = $( 'input:text', inputDiv ),

	//grab the form
		form = $( 'form', inputDiv ),

	//grab the default text
		defaultText = $( 'label', inputDiv ).html();



	//INIT

	//fill the input with the default text from the label
	input.val( defaultText );

	//HOVER

	//setup a mouseenter event
	inputDiv.delegate( 'input:text, textarea', 'mouseenter', function(){

		//show the green highlight by adding the active and hover class
		inputDiv.addClass( 'hover' ).addClass( 'active' );


	});

	//setup a mouseleave event
	inputDiv.delegate( 'input:text, textarea', 'mouseleave', function(){

		//remove the hover class
		inputDiv.removeClass( 'hover' );

		//if the input is out of focus remove the active class as well
		if( ! inputDiv.hasClass( 'focus' ) ){
			inputDiv.removeClass( 'active' );
		}

	});

	//FOCUS

	//setup a focus event
	inputDiv.delegate( 'input:text, textarea', 'focus', function(){

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
	inputDiv.delegate( 'input:text, textarea', 'blur', function(){

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
	form.submit(function( e ){

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
					wall.addPost( response );
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

});