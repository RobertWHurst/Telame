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

$this->Paginator->options(array(
	'url' => array(
		'controller' => 'groups_users',
		'action' => 'friendList',
		'slug' => $this->params['slug'],
	)
));

echo $this->Paginator->numbers();
echo '<br />';
echo $this->Paginator->counter();

pr($friends);

foreach($friends as $friend) {
//	echo $friend['Friend']['Profile']['full_name'];
}

?>