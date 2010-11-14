$(function(){

	var speed = 200;
	
	var methods = {
		'initCol': function( col ){

			//set the column to its default state
			if( col.hasClass('level_0') ){

				col.fadeIn( speed );

			}

			//create the sliders
			methods.initSild( col );

		},
		'initSild': function( col ){

			//create each switch
			col.find('div.slider').each(function(){

				//get the id of the current slider
				var id = '#' + $( this ).attr( 'id' );

				$( id ).complexSlider();

			});

		}
	}

	//selectors
	var permTbl = $( 'div.permissions_table' );
	var acoCols = $( 'div.aco_column', permTbl );
	var acoCells = $( 'div.cell', permTbl );
	
	//show the groups column
	$( 'div.groups_column' ).fadeIn( speed );

	//add classes for each radio input
	acoCells.each(function(){

		//find the radio inputs
		$( this ).find( 'input:radio' ).each(function(){
			var value = $( this ).val();

			switch( value ){
				case '0':
					$( this ).addClass( 'block' );
				break;
				case '1':
					$( this ).addClass( 'allow' );
				break;
				case '2':
					$( this ).addClass( 'inherit' );
				break;
			}
		});

	});

	//process each column
	var i = 0;
	acoCols.each(function(){

		//get the current column
		var col = $(this);
		
		setTimeout(function(){
			methods.initCol( col );
		}, i * 200);

		i += 1;
	});
});