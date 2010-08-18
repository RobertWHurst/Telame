$(function($){
	
	headerGlow = function(){
	
		//make a reference to this
		var root = this;	
	
		//TIMING SETTINGS
		root.max = 6000;
		root.min = 1500;	
		
		root.timeInt = Math.floor(root.min + (Math.random() * (root.max - root.min)));
		
		//save the background glow selector
		root.glow = $('#backgroundHeadGlow');
		
		//get the current level of opacity
		root.level = root.glow.css('opacity');		
		
		if(root.glow.hasClass('up')){
			root.opacity = 1;
			root.glow.removeClass('up').addClass('down');
		}
		else if(root.glow.hasClass('down')){	
			root.opacity = 0;
			root.glow.removeClass('down').addClass('up');
		}
		else{
			root.glow.fadeIn(root.timeInt).addClass('up');
		}
		
		//preform the animataion
		root.glow.animate({
			'opacity': root.opacity
		}, root.timeInt, headerGlow);
	};
	
	headerGlow();
	
});