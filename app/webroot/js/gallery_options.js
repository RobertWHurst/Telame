$(function(){

	//define a control object
	galleryOptions = {};

	var GalleryOptionsLogic = function(){
		
		//save this as root
		var root = this;
		
		//save the dom elements
		root.galleryOptionsContainer = $('#gallery_options');
		root.galleryOptionsExample = $('div.example', root.galleryOptionsContainer);
		root.slider = $('div.slider', root.galleryOptionsContainer);
		root.slideHandle = $('div.slide_handle', root.galleryOptionsContainer);
		root.currentImage = $('div.photos img:visible', root.galleryOptionsContainer);
		root.dragHandle = $('div.img_handle', root.galleryOptionsContainer);
		root.dragHandleWrap = $('div.img_handle_wrap', root.galleryOptionsContainer);
		root.allImages = $('div.photos img', root.galleryOptionsContainer);
		
		//the animation speed
		root.speed = 300;
		
		//sliderDragHandler
		root.exampleHandler = function(){
		
			//run when the current picture is loaded
			root.allImages.load(function(){
			
				//remove the nojs class from the gallery options
				root.galleryOptionsContainer.removeClass('nojs');
								
				//set vars
				var sliderWidth,
					sliderHandleWidth,
					sliderOffsetX,
					dragOffsetX,
					dragOffsetY,
					dragWrapOffsetX,
					dragWrapOffsetY,
					CurrentImageHeight,
					CurrentImageWidth,
					BaseImageHeight,
					BaseImageWidth,
					cursorOffsetX,
					cursorOffsetY,
					cursorPositionSliderX,
					clickPositionDragX,
					clickPositionDragY,
					cursorPositionDragWrapX,
					cursorPositionDragWrapY,
					sliderHandlePositionX,
					Imageoffset,
					dragHandleWidth,
					dragHandlePositionX,
					dragHandlePositionY;
   				
				//set the drag handle to the same size and offset as the current image		
				root.dragHandle.height(root.currentImage.height());
				root.dragHandle.width(root.currentImage.width());
				root.dragHandle.css(root.currentImage.position());		
   				
   				
   				
   				//get the slider width
   				sliderWidth = root.slider.width();
   				sliderHandleWidth = root.slideHandle.width();
				sliderOffsetX = root.slider.offset().left;
				
				//get the drag offset
				dragWrapOffsetX = root.dragHandleWrap.offset().left;
				dragWrapOffsetY = root.dragHandleWrap.offset().top;
				
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
				
				//get the current top and left offset
				baseImageOffset = root.currentImage.position();
				
				//set the height and width back to the saved current
				root.currentImage.height(CurrentImageHeight);
				root.currentImage.width(CurrentImageWidth);
				
				
							
				//track the cursor
				root.galleryOptionsContainer.mousemove(function(event){
      				cursorOffsetX = event.pageX;
      				cursorOffsetY = event.pageY;      				
      				cursorPositionSliderX = event.pageX - sliderOffsetX;    				
      				cursorPositionDragWrapX = event.pageX - dragWrapOffsetX;
      				cursorPositionDragWrapY = event.pageY - dragWrapOffsetY;     				
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
					if(cursorPositionSliderX <= sliderHandleWidth / 2){
						//if the cursor position is below the limit
						sliderHandlePositionX = 0;
					}
					else if(cursorPositionSliderX >= sliderWidth - (sliderHandleWidth / 2)){
						//if the cursor position is above the limit			
						sliderHandlePositionX = sliderWidth - sliderHandleWidth;
					}
					else{					
						//if the cursor position is within the limit
						sliderHandlePositionX = cursorPositionSliderX - (sliderHandleWidth / 2);
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
				
					//update the current dimentions of the image
					CurrentImageHeight = root.currentImage.height();
					CurrentImageWidth = root.currentImage.width();
				});
				
				
				
				//declare a function to scale the image.
				var scaleImage = function(percent){
					
					var newOffset = {};
					
					//set the scale factor
					scaleFactor = (percent + 50) / 100;
					
					//calculate the new width and height.
					newHeight = Math.round(BaseImageHeight * scaleFactor);
					newWidth = Math.round(BaseImageWidth * scaleFactor);
					
					//set the new height and width
					root.currentImage.height(newHeight);
					root.dragHandle.height(newHeight);
					root.currentImage.width(newWidth);
					root.dragHandle.width(newWidth);
					
					//calculate the new offset over hang
					newOffset.top = ((CurrentImageHeight - newHeight) / 2) + baseImageOffset.top;
					newOffset.left = ((CurrentImageWidth - newWidth) / 2) + baseImageOffset.left;
					
					root.currentImage.css(newOffset);
					root.dragHandle.css(newOffset);
				}
				
				//bind the scroll wheel
				root.galleryOptionsExample.mousewheel(function(event, delta){
					
					event.preventDefault();
					
					//get the current zoom
					zoom = getSliderValue();
					
					if (delta > 0){
					
						zoom += 3;
						
						if(zoom < 0){
							zoom = 0;
						}
						else if(zoom > 100){
							zoom = 100;
						}
						
						setSliderValue(zoom);
						scaleImage(getSliderValue());
					}
					else if (delta < 0){
					
						zoom -= 3;
						
						if(zoom < 0){
							zoom = 0;
						}
						else if(zoom > 100){
							zoom = 100;
						}
						
						setSliderValue(zoom);
						scaleImage(getSliderValue());
					}
				}); 
				
				//calculate the zoom of the image and set the slider
				zoom = CurrentImageWidth / BaseImageWidth * 100;
				setSliderValue(zoom / 2);
				
				
				
				//map the set and get value functions to the control object
				galleryOptions.zoom = function(newValue){
					if(newValue == undefined){
						return getSliderValue();
					}
					else{
						if(newValue > 100){
							newValue = 100;
						}
						else if(newValue < 0){
							newValue = 0;
						}
						setSliderValue(newValue);
						scaleImage(getSliderValue());
					}
				}
				
				
				
				//declare a function to scale the image.
				var setImagePosition = function(top, left){
					root.dragHandle.css({'top':top , 'left': left});
					root.currentImage.css({'top':top , 'left': left});
				}
				
				
				
				//decalare a function for draging the image around
				var dragImageHandle = function(){
									
					
					dragHandlePositionX = cursorPositionDragWrapX - clickPositionDragX;
					
					//check the range of x
					if(dragHandlePositionX < -(CurrentImageWidth / 2)){
						//if the cursor position is below the limit
						dragHandlePositionX = -(CurrentImageWidth / 2);
					}
					else if(dragHandlePositionX > (CurrentImageWidth / 2) + CurrentImageWidth){
						//if the cursor position is above the limit			
						dragHandlePositionX = (CurrentImageWidth / 2) + CurrentImageWidth;
					}			
					
					dragHandlePositionY = cursorPositionDragWrapY - clickPositionDragY;
					
					//check the range of x
					if(dragHandlePositionY < -(CurrentImageHeight / 2)){
						//if the cursor position is below the limit
						dragHandlePositionY = -(CurrentImageHeight / 2);
					}
					else if(dragHandlePositionY > (CurrentImageHeight / 2) + CurrentImageHeight){
						//if the cursor position is above the limit			
						dragHandlePositionY = (CurrentImageHeight / 2) + CurrentImageHeight;
					}
					
					setImagePosition(dragHandlePositionY, dragHandlePositionX);
					
				}
				
					
				root.dragHandle.mousedown(function(event){
					event.preventDefault();
					root.dragHandle.addClass('active');
					
					dragOffsetX = root.dragHandle.offset().left;
					dragOffsetY = root.dragHandle.offset().top;
						
					clickPositionDragX = event.pageX - dragOffsetX;
      				clickPositionDragY = event.pageY - dragOffsetY;
      				
					loop.newProccess('image_handle_drag', dragImageHandle, 1);
				});
				var end = function(){
					loop.killProccess('image_handle_drag');
					root.dragHandle.removeClass('active');
					baseImageOffset = root.currentImage.position();
				};
				$(window).mouseup(end);
				$(window).click(end);
			
			});	
			
		}
		
		//define the constructor
		root.construct = function(){
			
			root.exampleHandler();
		}
		
		//self execute
		root.construct();
		
	}
	
	new GalleryOptionsLogic;

});