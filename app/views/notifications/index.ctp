<?php
//INCLUDES
$this->set('css_for_layout', array(
	'base',
	'tall_header',
	'main_sidebar',
));
$this->set('script_for_layout', array(
	'jquery',
	'base',
	'header_glow',
	'main_sidebar',
));
?>
<div id="page_head" class="clearfix">			
	<h1 class="page_title"><?php echo __('notifications', true); ?></h1>
</div>
<div id="page_body" class="clearfix">
	<div id="notifications">
<?php
	pr($notifications);
?>
	</div>
</div>