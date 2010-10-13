$(function(){

    var messageLogic = function(){

        //save this as root
        var root = this;

        //save the dom elements
        root.messageComposerWrap = $('#create_message_wrap');
        root.messageComposerRecipientInputWrap = $('div.recipients', root.messageComposerWrap);
        root.messageComposerRecipientInput = $('#MessageUserSlugs', root.messageComposerRecipientInputWrap);

		//create data objects
		root.selectedUsers = {};
		
		//rebuild ui
		root.formatUi = function(){
			
			root.messageComposerRecipientInput
				.css({
					'background': 'transparent',
					'border': 'none'
				})
				.wrap('<div class="fake_input">')
				.before('<input id="full_name_input" type="text" />'),
			
			//bind to the new fake input
			root.fullNameInput = $('#full_name_input'),
			
			//execute the input handler
			root.inputValueHandler();
		}
		
        //post hover handler
        root.inputValueHandler = function(){
			
			//REMOVE THIS
			root.messageComposerRecipientInputWrap.append('<div id="hintList">');
			root.hintlist = $('#hintList');
			
			//set the timer
			var timer = proccessing = string = i = the_slugs = false;
			
			root.fullNameInput.keyup(function(event){
				
				//define the function that requests hint data
				var requestHint = function(){
					
					//set the string
					string = domElement.val().replace(' ', '_');
										
					//string length must be 2 char or longer to ping the server	
					if(string.length > 1){
															
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
				}
				
				//define function to create a hint row
				var addrow = function(userData){
					
					if(typeof userData !== 'object'){
						return false;
					}
					
					if(typeof root.selectedUsers[userData.slug] === 'undefined'){
					
						//build the row
						root.hintlist.append('<div id="' + userData.slug + '" class="row"><div class="user_name">' + userData.full_name + '</div><div class="email">' + userData.email + '</div></div>');
						
						//grab the rows
						$('div.row', '#hintList').click(function(){
							
							//save the dom element
							var domElement = $(this);
							
							addUser(domElement.attr('id'));
							
						});
					}
				}
				
				var addUser = function(slug){
						
						//add the user slug to the selected array
						root.selectedUsers[slug] = slug;
						
						updateSlugsInput();
				}
				
				var removeUser = function(slug){
						
						//add the user slug to the selected array
						root.selectedUsers.splice(slug);
						
						updateSlugsInput();
					
				}
				
				var updateSlugsInput = function(){
					//clear the input.val('');
					
					//add the slugs to the hidden slug input
					i = 1;
					for(k in root.selectedUsers){
						the_slugs += root.selectedUsers[k];
						
						if(i !== root.selectedUsers.length){
							the_slugs += ', ';
						}
						
						//adv the iderator
						i += 1;
					}
					root.messageComposerRecipientInput.val(the_slugs);
				}
				
				// save the dom element
				var domElement = $(this);
				
				//clear the list
				root.hintlist.empty();
				
				//get the hints
				requestHint();
				
			});
			
        }

        //define the constructor
        root.construct = function(){

            //bind all of the event handlers
            root.formatUi();

        }

        //self execute
        root.construct();

    }

    new messageLogic;

});