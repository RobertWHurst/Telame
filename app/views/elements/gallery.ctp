<div id="profile_gallery">
	<div class="controls">
		<!--<div class="view">
<?php 
			//$url = array('controller' => 'media', 'action' => 'gallery');
			//echo $html->image('icons/cog_edit.png', array('title' => __('gallery_options',true), 'url' => $url));
?>
		</div>-->
		<?php if($currentUser['User']['id'] == $user['User']['id']): ?>
			<div class="options">
<?php 
				$url = array('slug' => $currentUser['User']['slug'], 'controller' => 'settings', 'action' => 'gallery');
				echo $html->image('icons/cog_edit.png', array('title' => __('gallery_options',true), 'url' => $url));
?>
			</div>
		<?php endif; ?>
	</div>
	<div class="frame"></div>
	<div class="images">
<?php
			//get the gallery dispaly mode
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