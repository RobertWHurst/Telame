<?php
//INCLUDES
$this->set('css_for_layout', array(
	'base',
	'gallery',
	'summary',
	'wall_posts',
	'tall_header',
	'main_sidebar',
	'users/wall',
	'users/wall_sidebar'
));
$this->set('script_for_layout', array(
	'jquery',
	'base', 
	'main_sidebar',
	'users/wall_input',
	'users/wall'
));
//page title
$this->set('title_for_layout', __('site_name', true) . ' | ' . $user['User']['full_name']);

echo $this->element('users/wall'); ?>
<div id="wall_sidebar">
	<?php echo $this->element('users/friends'); ?>
</div>