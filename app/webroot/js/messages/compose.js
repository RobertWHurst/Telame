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
			root.messageComposerRecipientInputWrap.append('<div id="hintList">');
			root.hintlist = $('#hintList');
			
			//set the timer
			var timer = proccessing = string = i = false;
			
			root.messageComposerRecipientInput.keyup(function(event){
        		
        		if(proccessing === true){
        			proccessing = false;
        			clearTimeout(timer);
        		}
				
				//define the function that requests hint data
				var requestHint = function(){
					
					//set the string
					string = domElement.val().replace(' ', '_');
									
					$.get(core.domain + '/m/a/' + string, function(data){
						
						//clear the list
						root.hintlist.empty();
						
						if(data.length > 0){
							
							for(i in data){
								
								//create the row
								addrow(data[i]);
								
							}
						}
					});
				}
				
				//define function to create a hint row
				var addrow = function(userData){
					
					if(typeof userData !== 'object'){
						return false;
					}
					
					//build the row
					root.hintlist.append('<div class="row"><div class="user_name">' + userData.full_name + '</div><div class="email">' + userData.email + '</div></div>');
					
				}
				
				// save the dom element
				var domElement = $(this);
				
				//exit the function if the input is empty
				if(domElement.val() == ''){
					return false;	
				}
				
				//set a timeout so requests are only made when the user stops typing
				timer = setTimeout(requestHint, 500);
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