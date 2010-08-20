<?php
//INCLUDES
$this->set('css_for_layout', array(
	'base',
	'gallery',
	'summary',
	'tall_header',
	'main_sidebar',
	'users/profile',
	'albums/albums'
));
$this->set('script_for_layout', array(
	'jquery',
	'base',
	'header_glow',
	'main_sidebar',
	'albums/thumbs'
));
?>
<div id="newAlbum" class="clearfix">
<?php

echo $form->create();
echo $form->input('title');
echo $form->input('description');
echo $form->end(__('add_album', true));

?>
</div>