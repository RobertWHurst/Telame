<div id="gallery_options">
	<h1>Gallery Options</h1>
	<div id="example">
		<div id="frame"></div>
		<div id="photos">
<?php
	   		switch($user['Profile']['gallery_mode']):
	   			default: case 'single':
					//find the image offset (if exists)
	   				$top = $left = 0;
	   				foreach($galleryPosData as $coords){
	   					if($coords['id'] == $user['User']['avatar_id']){
	   						$top = $coords['y'];
	   						$left = $coords['x'];
	   						break;
	   					}
	   				}
	   				
	   				$url = array('controller' => 'media', 'action' => 'profile', $user['User']['avatar_id']);
	   				$options = array('style' => "top: {$top}px; left: {$left}px;");
	   				
	   				echo $this->Html->image($url, $options);
	   			
	   			case 'multi':
	   				echo '[profile pictures slideshow]';
	   				//TODO when the use has more than one image then loop through them here
	   				break;
	   		endswitch;
?>
		</div>
	</div>
	<?php if($user['Profile']['gallery_mode'] == 'multi'): ?>
		<div id="selector">
			thumb of each image will go here...
		</div>
	<?php endif; ?>
	<div id="slider">
		<div id="slide"></div>
	</div>
	<div id="info">
		<p>
			Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.
		</p>
	</div>
</div>