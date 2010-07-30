jQuery(document).ready(function($){
	
	//CONSTRUCTORS
	var construct = {
		//LOOP
		'loop': function(){
			
			//proccess Array
			this.stack = {};
			
			//save this
			_loop = this;
			
			//add a new proccess
			this.newProccess = function(key, callback, interval){
				//make sure that the callback is real and the interval is numeric
				if($.isFunction(callback) === false || typeof interval !== 'number'){
					return false;
				}
				
				//add the proccess to the loop stack			
				_loop.stack[key] = {'callback': callback, 'interval': interval, 'i': 0};
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
	loop = new construct.loop;
});