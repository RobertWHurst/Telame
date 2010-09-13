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
				$url = array('controller' => 'users', 'action' => 'gallery');
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
	   			break;
	   		case 'multi':
	   			echo '[profile pictures slideshow]';
	   			//TODO when the use has more than one image then loop through them here
	   			break;
	   	endswitch;
?>
	</div>
</div>