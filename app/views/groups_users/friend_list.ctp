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
	'profile',
	'main_sidebar',
	'users/wall_input',
	'users/wall'
);
foreach ($js as $j) {
	$javascript->link($j, false);
}

//page title

echo $paginator->numbers();
echo '<br />';
echo $paginator->counter();

pr($friends);

foreach($friends as $friend) {
//	echo $friend['Friend']['Profile']['full_name'];
}

?>