<?php
//INCLUDES
$html->css(array(
	'base',
	'gallery',
	'summary',
	'tall_header',
	'main_sidebar',
	'users/wall',
	'users/wall_sidebar'
), null, array('inline' => false));
$js = array(
	'jquery',
	'base',
	'main_sidebar',
	'users/wall_input',
	'users/wall'
);
foreach ($js as $j) {
	$javascript->link($j, false);
}
	echo $form->create('Media', array('type' => 'file'));
	echo $form->input('file', array('type' => 'file'));
	echo $form->input('title');
	echo $form->input('album', array('options' => $albums));
	echo $form->end(__('upload', true));

?>
