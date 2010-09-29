<?php
//INCLUDES
$html->css(array(
	'base',
	'gallery',
	'summary',
	'wall_posts',
	'tall_header',
	'main_sidebar',
	'wall',
	'users/wall_sidebar'
), null, array('inline' => false));
$js = array(
	'jquery',
	'base',
	'profile',
	'main_sidebar',
	'wall_input',
	'wall'
);
foreach ($js as $j) {
	$javascript->link($j, false);
}
//page title
$this->set('title_for_layout', $user['User']['full_name']);

echo $this->element('wall'); ?>
<div id="wall_sidebar">
	<?php echo $this->element('users/friends'); ?>
</div>