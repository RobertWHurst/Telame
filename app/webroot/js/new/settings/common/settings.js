jQuery(function($) {

	$( '#test' ).lockSwitch();
	$( '#test2' ).lockSwitch({'parent':'#test'});
	$( '#test3' ).lockSwitch({'parent':'#test2'});
	$( '#test4' ).lockSwitch({'parent':'#test3'});

});
