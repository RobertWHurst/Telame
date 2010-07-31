$(document).ready(function(){

	var sidebar_logic = function(){
		
		//save this as root
		var root = this;
		
		//save the dom elements
		root.searchInput = $('#searchQuery', '#main_sidebar');
		root.searchInputLabel = $('div.search label', '#main_sidebar');
		root.searchInputWrap = $('div.search', '#main_sidebar');
		
		//the animation speed
		root.speed = 300;
		
		//input wrap hover handler
		root.hoverHandler = function(action){
			
			//check the state
			if(action === 'in'){
				
				//remove the old active class and add the inactive class
				root.searchInputWrap.addClass('active').addClass('hover');
				
			}
			else if(action === 'out'){
				
				//remove the old active class and add the inactive class
				root.searchInputWrap.removeClass('hover');			
				if(root.searchInputWrap.hasClass('focus') === false){			
					root.searchInputWrap.removeClass('active');
				}	
				
			}
				
		}
		
		//input focus handler
		root.focusHandler = function(action){
			
			//check the state
			if(action === 'in'){
				
				//remove the old active class and add the inactive class
				root.searchInputWrap.addClass('active').addClass('focus');
				
				//hide the label
				root.searchInputLabel.hide();
				
			}
			else if(action === 'out'){
				
				//remove the old active class and add the inactive class
				root.searchInputWrap.removeClass('focus');			
				if(root.searchInputWrap.hasClass('hover') === false){			
					root.searchInputWrap.removeClass('active');
				}	
								
				//if the inupt or textarea is empty then hide the label
				if(root.searchInput.val() === ''){
					root.searchInputLabel.show();
				}
			}
				
		}
		
		//define the constructor
		root.construct = function(){
			
			//on hover event
			root.searchInputWrap.hover(function(){
				root.hoverHandler('in');
			},
			function(){
				root.hoverHandler('out');
			});
			
			//on focus event
			root.searchInput.focus(function(){
				root.focusHandler('in');
			});
			root.searchInput.blur(function(){
				root.focusHandler('out');
			});
			
		}
		
		//self execute
		root.construct();
		
	}
	
	new sidebar_logic;

});