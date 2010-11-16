$(function(){

	var speed = 300;
	
	var methods = {
		'initCol': function( col, parent ){

			//set the column to its default state
			if( col.hasClass( 'level_0' ) ){

				//fade in the column
				col.fadeIn( speed );

				//create the sliders
				methods.initSild( col );

				//self execute on the children
				$( 'div.parent_' + col.attr( 'id' ) ).each(function(){
					chld = $( this );
					methods.initSild( chld, col );
				});

			}
			
		},
		'initSild': function( col, parent ){

			//create each switch
			col.find('div.slider').each(function(){

				var slider = $( this );
				//get the id of the current slider
				var id = '#' + slider.attr( 'id' );

				if( parent ){

					//get the current group
					var cell = slider.parent( 'div.cell' );
					var cellGrpCls = methods.getCelGrpCls( cell );

					//find the parent slide in the parent col
					//and make sure its been created. if not
					//re try in 1/2 a second.
					var sldrParnt = parent.find( 'div.' + cellGrpCls + ' div.TS_slider' );
					if( sldrParnt ){
						$( id ).complexSlider({ 'parent': sldrParnt });
					}

				} else {
					$( id ).complexSlider();

				}

			});

		},
		'showChldrn': function( col ){

			//fadeout the siblings
			col.siblings( 'div.aco_column' ).fadeOut( speed );
			
			//fade in the children
			$( 'div.parent_' + col.attr( 'id' ) ).fadeIn( speed );
			
		},
		'getCelGrpCls': function( cell ){
			var grpClses = cell.attr( 'class' );
			grpClses = grpClses.split( ' ' );

			//find the groupClass
			var grpCls = false;
			for( var k in grpClses ){
				if( grpClses[k].match(/group_/) ){
					grpCls = grpClses[k];
					break;
				}
			}

			//return the group class
			return grpCls;
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
		}, i * speed);

		i += 1;

		//bind the link to show column children
		col.find('div.heading a').click(function(){
			methods.showChldrn( col );
		});
	});

});