// I'm sure you will want to change this, put it somewhere different, or whatever, but for now it works ;)
function changeImg() {
	document.getElementById("notifications").src = (document.getElementById("notifications").src.indexOf("icons/asterisk_yellow.png") == -1)?"/img/icons/asterisk_yellow.png":"/img/icons/asterisk_orange.png"; 
}


$(function($){

	core = {
		'domain': 'http://' + window.location.hostname,
	};
	
	//CONSTRUCTORS
	construct = {
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
			
			this.killProccess = function(key){
				delete _loop.stack[key];
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
	
	// the flash logic
	flashLogic = function(){
		
		//set the root for easy access
		var root = this;
		
		//store the selectors
		root.flashWrap = $('#flashWrapper');
		root.flash = $('#flash');
		root.flashMessages = $('div', '#flash');
		
		//create a function for displaying a flash message via javascript
		root.setMessage = function(key, message){
			
			//create the message within a jquery object
			var message = $('<div class="' + key + '">' + message + '</div>');
			
			//hide the message
			message.hide();
			
			//append it the the flash container
			root.flashWrap.children('#flash').prepend(message);
			
			//animate it in
			message.slideDown(600);
						
			//after ten seconds hide the messages
			setTimeout(function(){
				message.slideUp(600, function(){
					$(this).remove();
				});				
			}, 10000);
			
		}
		
		//create a handler for closing messages manually
		root.closeHandler = function(){
			
			root.flash.delegate('div', 'click', function(){
			
				var domElement = $(this);
				
				domElement.slideUp(300, function(){
					$(this).remove();
				});
			
			});
		}
		
		//create a constructor
		root.construct = function(){
		
			//set the flash wrapper to fixed position
			root.flashWrap.css({
				'position': 'fixed',
				'top': 0,
				'width': '100%',
				'opacity': 0.9,
				'z-index': 9999,
				'cursor': 'pointer'
			});
			
			//slide down the messages
			root.flashMessages.hide().slideDown(600);
			
			//after ten seconds hide the messages
			setTimeout(function(){
				root.flashMessages.slideUp(600, function(){
					$(this).remove();
				});				
			}, 10000);
			
			//execute the handlers
			root.closeHandler();
			
		}
		
		root.construct();
	}
	
	flash = new flashLogic;
});

//EXPANDING TEXTAREA PLUGIN
(function($) {

    /*
* Auto-growing textareas;
*/
    $.fn.autogrow = function(options) {
        
        this.filter('textarea').each(function() {
            
            var $this = $(this),
                minHeight = $this.height(),
                lineHeight = $this.css('lineHeight');
            
            var shadow = $('<div class="autogrow_shadow"></div>').css({
                position: 'absolute',
                top: -10000,
                left: -10000,
                width: $(this).width() - parseInt($this.css('paddingLeft')) - parseInt($this.css('paddingRight')),
                fontSize: $this.css('fontSize'),
                fontFamily: $this.css('fontFamily'),
                lineHeight: $this.css('lineHeight'),
                resize: 'none'
            }).appendTo(document.body);
            
            var update = function() {
    
                var times = function(string, number) {
                    for (var i = 0, r = ''; i < number; i ++) r += string;
                    return r;
                };
                
                var val = this.value.replace(/</g, '&lt;')
                                    .replace(/>/g, '&gt;')
                                    .replace(/&/g, '&amp;')
                                    .replace(/\n$/, '<br/>&nbsp;')
                                    .replace(/\n/g, '<br/>')
                                    .replace(/ {2,}/g, function(space) { return times('&nbsp;', space.length -1) + ' ' });
                
                shadow.html(val);
                $(this).css('height', Math.max(shadow.height() + 20, minHeight));
            
            }
            
            $(this).change(update).keyup(update).keydown(update);
            
            update.apply(this);
            
        });
        
        return this;
        
    }
    
})(jQuery);