<div id="gallery_options" class="nojs">
	<h1>Gallery Options</h1>
	<div class="example">
		<div class="directional">
			<div class="up">
<?php 
				$url = array('controller' => 'wall_posts', 'action' => 'delete');
				echo $html->image('icons/arrow_up.png', array('title' => __('up',true), 'url' => $url));
?>
			</div>
			<div class="down">
<?php 
				$url = array('controller' => 'wall_posts', 'action' => 'delete');
				echo $html->image('icons/arrow_down.png', array('title' => __('down',true), 'url' => $url));
?>
			</div>
			<div class="left">
<?php 
				$url = array('controller' => 'wall_posts', 'action' => 'delete');
				echo $html->image('icons/arrow_left.png', array('title' => __('left',true), 'url' => $url));
?>
			</div>
			<div class="right">
<?php 
				$url = array('controller' => 'wall_posts', 'action' => 'delete');
				echo $html->image('icons/arrow_right.png', array('title' => __('right',true), 'url' => $url));
?>
			</div>
		</div>
		<div class="img_handle_wrap">
			<div class="img_handle"></div>
		</div>
		<div class="frame"></div>
		<div class="photos">
<?php
	   		switch($user['Profile']['gallery_mode']):
	   			default: case 'single':
					//find the image offset (if exists)
	   				$top = $left = 0;
	   				foreach($galleryPosData as $coords){
	   					if($coords['id'] == $user['User']['avatar_id']){
	   						$top = $coords['y'];
	   						$left = $coords['x'];
	   						$height = $coords['h'];
	   						$width = $coords['w'];
	   						break;
	   					}
	   				}
	   				
	   				$url = array('controller' => 'media', 'action' => 'profile', $user['User']['avatar_id']);
	   				$options = array('style' => "top: {$top}px; left: {$left}px; height: {$height}px; width: {$width}px;");
	   				
	   				echo $this->Html->image($url, $options);
	   				break;
	   				
	   			case 'multi':
	   				echo '[profile pictures slideshow]';
	   				//TODO when the use has more than one image then loop through them here
	   				break;
	   		endswitch;
?>
		</div>
	</div>
	<?php if($user['Profile']['gallery_mode'] == 'multi'): ?>
		<div class="selector">
			thumb of each image will go here...
		</div>
	<?php endif; ?>
	<div class="zoom">
		<div class="zoom_out">
<?php 
			$url = array('controller' => 'wall_posts', 'action' => 'delete');
			echo $html->image('icons/zoom_out.png', array('title' => __('zoom_out',true), 'url' => $url));
?>
		</div>
		<div class="slider">
			<div class="slide_handle"></div>
		</div>
		<div class="zoom_in">
<?php 
			$url = array('controller' => 'wall_posts', 'action' => 'delete');
			echo $html->image('icons/zoom_in.png', array('title' => __('zoom_in',true), 'url' => $url));
?>
		</div>
	</div>
	<div class="info">
		<p>
			Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.
		</p>
	</div>
</div>