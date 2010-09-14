$(function(){

	//define a control object
	galleryOptions = {};

	var GalleryOptionsLogic = function(){
		
		//save this as root
		var root = this;
		
		//save the dom elements
		root.galleryOptionsContainer = $('#gallery_options');
		root.slider = $('div.slider', root.galleryOptionsContainer);
		root.slideHandle = $('div.slide_handle', root.galleryOptionsContainer);
		root.currentImage = $('div.photos img:visible', root.galleryOptionsContainer);
		
		//the animation speed
		root.speed = 300;
		
		//sliderDragHandler
		root.slideHandleDragHandler = function(){
				
			//set vars
			var sliderWidth,
				sliderHandleWidth,
				sliderOffsetX,
				CurrentImageHeight,
				CurrentImageWidth,
				BaseImageHeight,
				BaseImageWidth,
				cursorOffsetX,
				cursorPositionX,
				sliderHandlePositionX;		
   			
   			
   			
   			//get the slider width
   			sliderWidth = root.slider.width();
   			sliderHandleWidth = root.slideHandle.width();
			sliderOffsetX = root.slider.offset().left;
			
			
			
			//save the current dimentions of the image	
			CurrentImageHeight = root.currentImage.height();
			CurrentImageWidth = root.currentImage.width();
			
			//strip the forced image size
			root.currentImage.css({
				'height': 'auto',
				'width': 'auto'
			});
			
			//save the base image size
			BaseImageHeight = root.currentImage.height();
			BaseImageWidth = root.currentImage.width();
			
			//set the height and width back to the saved current
			root.currentImage.height(CurrentImageHeight);
			root.currentImage.width(CurrentImageWidth);
			
			
						
			//track the cursor
			root.galleryOptionsContainer.mousemove(function(event){
      			cursorOffsetX = event.pageX;
      			cursorPositionX = event.pageX - sliderOffsetX;
   			});
			
			
			
			//declare a function to move the slider into position and update the value
			var setSliderPosition = function(newPostion){
				root.slideHandle.css('left', newPostion); 
			}
			
			
			
			//declare a function for moving the slider
			var dragSliderHandle = function(){
			
				//get the handle position
				sliderHandlePositionX = root.slideHandle.position().left;
			
				//check the range
				if(cursorPositionX <= sliderHandleWidth / 2){
					//if the cursor position is below the limit
					sliderHandlePositionX = 0;
				}
				else if(cursorPositionX >= sliderWidth - (sliderHandleWidth / 2)){
					//if the cursor position is above the limit			
					sliderHandlePositionX = sliderWidth - sliderHandleWidth;
				}
				else{					
					//if the cursor position is within the limit
					sliderHandlePositionX = cursorPositionX - (sliderHandleWidth / 2);
				}
				
				setSliderPosition(sliderHandlePositionX);
				
				scaleImage(getSliderValue());
			}
			
			
			
			//declare a function to set slider value (0 - 100)
			var setSliderValue = function(newValue){
			
				if(!typeof newValue == 'number')
					return false;
				
				sliderHandlePositionX = newValue * (sliderWidth - 16) / 100;
				setSliderPosition(sliderHandlePositionX);
			}
			
			
			
			//declare a function to get slider value
			var getSliderValue = function(){
				return sliderHandlePositionX * 100 / (sliderWidth - 16);
			}
			
			
			
			root.slider.mousedown(function(event){
				event.preventDefault();
				root.slideHandle.addClass('active');
				loop.newProccess('slide_handle_drag', dragSliderHandle, 1);
			});
			$(window).mouseup(function(){
				loop.killProccess('slide_handle_drag');
				root.slideHandle.removeClass('active');
			});
			
			
			
			//declare a function to scale the image.
			scaleImage = function(percent){
				
				//set the scale factor
				scaleFactor = (percent - 50) / 100;
				
				//calculate the new width and height.
				newHeight = Math.round(BaseImageHeight * scaleFactor);
				newWidth = Math.round(BaseImageWidth * scaleFactor);
				
				//set the new height and width
				root.currentImage.height(newHeight);
				root.currentImage.width(newWidth);
				
			}
			
			
			
			//calculate the zoom of the image and set the slider
			zoom = CurrentImageWidth / BaseImageWidth * 100;
			setSliderValue(zoom);
			
			
			
			//map the set and get value functions to the control object
			galleryOptions.zoom = function(newValue){
				if(newValue == undefined){
					return getSliderValue();
				}
				else{
					setSliderValue(newValue);
				}
			}			
			
		}
		
		//define the constructor
		root.construct = function(){
			
			//remove the nojs class from the gallery options
			root.galleryOptionsContainer.removeClass('nojs');
			
			root.slideHandleDragHandler();
		}
		
		//self execute
		root.construct();
		
	}
	
	new GalleryOptionsLogic;

});