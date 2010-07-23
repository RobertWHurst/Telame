jQuery(document).ready(function($){
	
	var animate = {
		
		//SCROLLING TAGLINES
		'taglines': function(){
		
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
			
			//animate the transition
			
		}
	}
	
	//CONSTRUCTORS
	var construct = {
		//LOOP
		'loop' : function(){
			
			//proccess Array
			this.stack = [];
			
			//save this
			_loop = this;
			
			//add a new proccess
			this.newProccess = function(callback, interval){
				//make sure that the callback is real and the interval is numeric
				if($.isFunction(callback) === false || typeof interval !== 'number'){
					return false;
				}
				
				//add the proccess to the loop stack			
				_loop.stack.push({'callback': callback, 'interval': interval, 'i': 0});
			}
			
			//the run switch for the loop
			this.isActive = true; 
			
			//the function the executes the loop
			this.runtime = function(){
				
				//if the "isActive switch is false skip a cycle
				if(_loop.isActive !== true){
					return;
				}
				else{
					for(stackKey in _loop.stack){
						
						//take the process out of the stack
						var proccess = _loop.stack[stackKey];
						
						if(proccess.i === 0){
							//if the proccess internal iderator is at zero run the proccess
							//and reset the internal iderator back to the use set interval
							proccess.callback();
							proccess.i = proccess.interval;
						}
						else{
							//remove one from the internal iderator
							proccess.i -= 1;
						}
						
						//put the process back in the stack
						_loop.stack[stackKey] = proccess;
						
					}
				}
			}
			
			//start the runtime loop
			setInterval(this.runtime, 0);
	
		}
	}
	
	//create the loop
	var loop = new construct.loop;
	
	//add the tagline animation to the loop
	loop.newProccess(animate.taglines, 400);
});