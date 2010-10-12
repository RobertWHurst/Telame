$(function(){

    var messageLogic = function(){

        //save this as root
        var root = this;

        //save the dom elements
        root.messageComposerWrap = $('#create_message_wrap');
        root.messageComposerInput = $('div.recipients input', root.messageComposerWrap);

        //post hover handler
        root.inputValueHandler = function(){
			
			//REMOVE THIS
			$('body').append('<div id="temp">');
			
			root.messageComposerInput.change(function(event){
				
				// save the dom element
				var domElement = $(this);
				
				$('#temp').html(domElement.val());
				
			});
			
        }

        //define the constructor
        root.construct = function(){

            //bind all of the event handlers
            root.inputValueHandler();

        }

        //self execute
        root.construct();

    }

    new messageLogic;

});