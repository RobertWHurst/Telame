<div id="profile_gallery">
	<div class="frame"></div>
	<div class="images">
<?php
	   	//get the gallery dispaly mode (single == 1 or multi == 2)
	   	switch($user['User']['UserSettings']['gallery_mode']):
	   		case 1:
	   			$url = $html->url(array('controller' => 'media', 'action' => 'profile', $user['User']['id']));
	   			echo $this->Html->image($url);
	   			break;
	   		case 2:
	   			echo '[profile pictures slideshow]';
	   			//TODO when the use has more than one image then loop through them here
	   			break;
	   	endswitch;
?>
	</div>
</div>