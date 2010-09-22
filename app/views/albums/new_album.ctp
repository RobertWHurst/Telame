<?php
//INCLUDES
$html->css(array(
	'base',
	'gallery',
	'summary',
	'tall_header',
	'main_sidebar',
	'albums/albums'
), null, array('inline' => false));
$js = array(
	'jquery',
	'base',
	'profile',
	'main_sidebar',
);
foreach ($js as $j) {
	$javascript->link($j, false);
}


?>
<div id="newAlbum" class="clearfix">
<?php

echo $form->create('Album', array('url' => array('slug' => $user['User']['slug'], 'controller' => 'albums', 'action' => 'newAlbum')));
echo $form->input('title');
echo $form->input('description');
echo $form->end(__('add_album', true));

?>
</div>