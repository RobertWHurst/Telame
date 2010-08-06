<div id="profile_gallery">
	<div class="frame"></div>
	<div class="images">
<?php
	   	//get the gallery dispaly mode
	   	switch($user['Profile']['gallery_mode']):
	   		default: case 'single':
	   			$url = $html->url(array('controller' => 'media', 'action' => 'profile', $user['User']['id']));
	   			echo $this->Html->image($url);
	   			break;
	   		case 'multi':
	   			echo '[profile pictures slideshow]';
	   			//TODO when the use has more than one image then loop through them here
	   			break;
	   	endswitch;
?>
	</div>
</div>