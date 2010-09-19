<div id="gallery_options" class="nojs">
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
	   			
	   			
	   			
	   				//setup the image url
	   				$url = array('controller' => 'media', 'action' => 'profile', $user['User']['avatar_id']);
	   					   				
					//find the image offset (if exists)	   				
	   				if(isset($galleryPosData[$user['User']['avatar_id']])){	
	   					$coords = $galleryPosData[$user['User']['avatar_id']];
	   					$top = $coords['y'];
	   					$left = $coords['x'];
	   					$height = $coords['h'];
	   					$width = $coords['w'];
	   				}
	   				
	   				
	   				//options default
	   				$options = array('id' => "image-{$user['User']['avatar_id']}");
	   				
	   				
	   				//if there are coords
	   				if(isset($coords))
	   					$options = array('id' => "image-{$user['User']['avatar_id']}", 'style' => "top: {$top}px; left: {$left}px; height: {$height}px; width: {$width}px;");
	   				
	   				
	   				//echo the image tag
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
</div>