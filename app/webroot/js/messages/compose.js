$(function(){

    var messageLogic = function(){

        //save this as root
        var root = this;

        //save the dom elements
        root.messageComposerWrap = $('#create_message_wrap');
        root.messageComposerRecipientInputWrap = $('div.recipients', root.messageComposerWrap);
        root.messageComposerRecipientInput = $('#MessageUserSlugs', root.messageComposerRecipientInputWrap);

		//rebuild ui
		root.formatUi = function(){
			
			root.messageComposerRecipientInput
				.css({
					'background': 'transparent',
					'border': 'none'
				})
				.wrap('<div class="fake_input">');
			
		}
		
        //post hover handler
        root.inputValueHandler = function(){
			
			//REMOVE THIS
			$('body').append('<div id="temp">');
			
			//set the timer
			var timer;
			var proccessing = false;
			
			root.messageComposerRecipientInput.keyup(function(event){
        		
        		if(proccessing === true){
        			proccessing = false;
        			clearTimeout(timer);
        		}
				
				//define the function that requests hint data
				var requestHint = function(){
					$.get(core.domain + '/m/a/' + domElement.val(), function(data){
						console.log(data);
					});
				}
				
				// save the dom element
				var domElement = $(this);
				
				//exit the function if the input is empty
				if(domElement.val() == ''){
					return false;	
				}
				
				//set a timeout so requests are only made when the user stops typing
				timer = setTimeout(requestHint, 1000);
				proccessing = true;
				
			});
			
        }

        //define the constructor
        root.construct = function(){

            //bind all of the event handlers
            root.inputValueHandler();
            //root.formatUi();

        }

        //self execute
        root.construct();

    }

    new messageLogic;

});