$(function(){

	var ProfileLogic = function(){
		
		//save this as root
		var root = this;
		
		//save the dom elements
		root.gallery = $('#profile_gallery', '#page_head');
		root.galleryControls = $('div.controls', root.gallery);
				
		//the animation speed
		root.speed = 300;
		
		//input wrap hover handler
		root.galleryhoverHandler = function(){
		
			//on hover event for each post comment
			root.gallery.hover(function(){
				root.galleryControls.show();
			},
			function(){
				root.galleryControls.hide();
			});
				
		}
		
		//define the constructor
		root.construct = function(){
			
			root.galleryhoverHandler();
			
		}
		
		//self execute
		root.construct();
		
	}
	
	new ProfileLogic;

});