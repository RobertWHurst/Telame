<div id="profile_gallery">
	<div class="frame"></div>
	<div class="images">
<?php
	   	//get the gallery dispaly mode
	   	switch($user['Profile']['gallery_mode']):
	   		default: case 'single':
	   			echo $this->Html->image(array('controller' => 'media', 'action' => 'profile', $user['User']['avatar_id']));	   				
	   			break;
	   		case 'multi':
	   			echo '[profile pictures slideshow]';
	   			//TODO when the use has more than one image then loop through them here
	   			break;
	   	endswitch;
?>
	</div>
</div>