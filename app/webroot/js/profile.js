$(function(){

	var ProfileLogic = function(){
		
		//save this as root
		var root = this;
		
		//save the dom elements
		root.gallery = $('#profile_gallery', '#page_head');
		root.galleryControls = $('div.controls', root.gallery);
		root.galleryOptionsPane = $('div.options_pane', root.gallery);
				
		//the animation speed
		root.speed = 300;
		
		//gallyer hover handler
		root.galleryhoverHandler = function(){
		
			//on hover event for each post comment
			root.gallery.hover(function(){
				root.galleryControls.show();
			},
			function(){
				root.galleryControls.hide();
			});
				
		}
		
		//gallery options click handler
		root.galleryOptionsClickHandler = function(){
		
			//on hover event for each post comment
			root.gallery.click(function(){
				root.galleryOptionsPane.show();
			},
			function(){
				root.galleryOptionsPane.hide();
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