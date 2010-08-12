$(function($){
	
	taglines = function(){
	
		//make a reference to this
		var _taglines = this;
		
		//set the width of a tagline
		var tagWidth = 980;
		
		//grab the stage element
		var stage = $('div.stage', '#taglines');
		
		//count the number of tags
		var tagCount = stage.children('p').size();
		
		//expand the width of the stage
		stage.width(tagWidth * tagCount);
		
		//note if the funciton is runing for the first time
		if(typeof this.firstExec === 'undefined'){
			this.firstExec = true;
		}
		
		//if it is the first exec then animate the fadeIn
		if(this.firstExec === true){
			stage.hide().fadeIn(600);
			this.firstExec = false;				
			//end the cycle here as we want to wait a cycle before moving the message
			return;
		}
		
		//findout the current offset and add one tag with to it,
		//if the last tag is reached then reset
		var stagePosition = stage.position();
		
		//caculate the new position
		if(stagePosition.left > -( tagWidth * (tagCount - 1) )){
			stage.animate({
				'left': stagePosition.left - tagWidth
			}, 1000);
		}
		else{
			stage.fadeOut(300, function(){
				stage.css('left', 0).fadeIn(600);
			});		
		}
	}
	
	//add the tagline animation to the loop
	loop.newProccess('taglines', taglines, 400);
});