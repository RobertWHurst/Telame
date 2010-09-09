<?php
//INCLUDES
$this->set('css_for_layout', array(
	'base',
	'gallery',
	'summary',
	'tall_header',
	'main_sidebar',
	'settings/settings'
));
$this->set('script_for_layout', array(
	'jquery',
	'base',
	'main_sidebar',
	'settings/settings'
));
//page title
$this->set('title_for_layout', __('site_name', true) . ' | ' . $user['Profile']['full_name'] . '\'s ' . __('settings', true));
?>
<div id="page_head">
	<h1 class="page_title"><?php echo __('groups_title', true); ?></h1>
</div>
<div id="page_navigation" class="clearfix">
	<?php echo $this->element('settings/navigation'); ?>
</div>
<div id="page_body" class="clearfix">
	<div id="permissions">
<?php
	pr($groups);
?>
	</div>
</div>