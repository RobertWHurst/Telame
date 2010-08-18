$(function($){
	
	headerGlow = function(){
	
		//make a reference to this
		var root = this;	
	
		//TIMING SETTINGS
		root.max = 8000;
		root.min = 3000;	
		
		root.timeInt = Math.floor(root.min + (Math.random() * (root.max - root.min)));
		
		//save the background glow selector
		root.glow = $('#backgroundHeadGlow');
		
		//get the current level of opacity
		root.level = root.glow.css('opacity') | 0;		
		
		if(root.glow.hasClass('up')){
			root.opacity = 1;
		}
		else if(root.glow.hasClass('down')){	
			root.opacity = 0;
		}
		else{
			root.glow.fadeIn(root.timeInt).addClass('up');
		}
			
		//if we hit the top reverse the trend
		if(root.level > 0.9){
			root.glow.removeClass('up').addClass('down');
		}	
		
		//if we hit the top reverse the trend
		if(root.level < 0.1){
			root.glow.removeClass('down').addClass('up');
		}
		
		root.glow.stop().animate({
			'opacity': root.opacity
		}, root.timeInt);
		
		setTimeout(headerGlow, root.timeInt);
	};
	
	headerGlow();
	
});