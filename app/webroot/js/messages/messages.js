$(function(){

	var messages_logic = function(){
		
		//save this as root
		var root = this;
		
		//save the dom elements
		root.messages = $('div.message', '#messages');
		root.messagesContainer = $('#messages', '#content');
		root.messagesForm = $('#MessageInboxForm', '#messages');
		root.controlsContainer = $('#controls', '#messages');
		root.deleteMessagesButton = $('a.delete', '#controls');
		root.umarkMessagesButton = $('a.unmark', '#controls');
		
		//the animation speed
		root.speed = 300;
		
		//input delete handler
		root.deleteHandler = function(){
			
			
			
		}
		
		//input unmark handler
		root.unmarkHandler = function(){	
		
		
		
					
		}
		
		//input delete selected handler
		root.deleteSelectedHandler = function(){
		
			//on the event that someone clicks the delete selected button
			root.deleteMessagesButton.live('click', function(event){
				event.preventDefault();			
				
				//save the form
				var formData = root.messagesForm.serialize();
				
				//send the data to the delete action
				$.get(core.domain + '/jx/m/d', formData, function(data){
					if(data === 'true'){
						alert('deleted');
					}
					else{						
						alert(data);
					}
				});
				
			});
			
		}
		
		//input unmark selected handler
		root.unmarkSelectedHandler = function(){	
		
			//on the event that someone clicks the delete selected button
			root.umarkMessagesButton.live('click', function(event){
				event.preventDefault();
						
				//save the form
				var formData = root.messagesForm.serialize();
				
				//send the data to the delete action
				$.post(core.domain + '/jx/m/mu', formData, function(data){
					if(data == 'true'){
						alert('unmarked');
					}
					else{						
						alert(data);
					}
				});
				
			});
					
		}
		
		//define the constructor
		root.construct = function(){
		
			//show the controls and checkboxes
			root.controlsContainer.show();
			root.messages.children('div.check').show();
			
			//bind all of the event handlers
			root.deleteHandler();
			root.unmarkHandler();
			root.deleteSelectedHandler();
			root.unmarkSelectedHandler();
			
		}
		
		//self execute
		root.construct();
		
	}
	
	new messages_logic;
	
});
