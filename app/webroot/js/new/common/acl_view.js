$(function(){

	var speed = 400;
	
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
	setTimeout(function(){
		$( 'div.groups_column' ).fadeIn( speed );
	}, speed);

	//process each column
	acoCols.each(function(){

		//get the current column
		var col = $(this);
		methods.initCol( col );

	});
});