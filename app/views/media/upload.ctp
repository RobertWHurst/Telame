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
	echo $this->element('media/upload');
?>
