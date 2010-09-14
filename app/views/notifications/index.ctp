<?php
//INCLUDES
$html->css(array(
	'base',
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