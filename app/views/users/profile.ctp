<?php
//INCLUDES
$html->css(array(
	'base',
	'gallery',
	'summary',
	'wall_posts',
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
$this->set('title_for_layout', __('site_name', true) . ' | ' . $user['User']['full_name']);

echo $this->element('users/wall'); ?>
<div id="wall_sidebar">
	<?php echo $this->element('users/friends'); ?>
</div>