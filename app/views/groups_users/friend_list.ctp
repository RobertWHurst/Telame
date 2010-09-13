<?php
//INCLUDES
$this->set('css_for_layout', array(
	'base',
	'gallery',
	'summary',
	'tall_header',
	'main_sidebar',
	'users/wall',
	'users/wall_sidebar'
));
$this->set('script_for_layout', array(
	'jquery',
	'base',
	'profile',
	'main_sidebar',
	'users/wall_input',
	'users/wall'
));
//page title

echo $paginator->numbers();
echo '<br />';
echo $paginator->counter();

pr($friends);

foreach($friends as $friend) {
//	echo $friend['Friend']['Profile']['full_name'];
}

?>