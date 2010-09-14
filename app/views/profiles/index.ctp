<?php
//INCLUDES
$html->css(array(
	'base',
	'gallery',
	'summary',
	'tall_header',
	'main_sidebar',
), null, array('inline' => false));
$js = array(
	'jquery',
	'base',
	'main_sidebar',
);
foreach ($js as $j) {
	$javascript->link($j, false);
}
?>
<div id="userControls" class="clearfix">
</div>