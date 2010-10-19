<?php
//INCLUDES
$html->css(array(
	'base',
	'tall_header',
), null, array('inline' => false));
$js = array(
	'jquery',
	'base',
);
foreach ($js as $j) {
	$javascript->link($j, false);
}
$this->set('title_for_layout', __('brewery_comments', true));

?>
<div id="page_head" class="clearfix">
	<h1 class="page_title"><?php echo __('brewery_comments', true); ?></h1>
</div>
<div id="page_navigation" class="clearfix">

</div>
<div id="page_body" class="clearfix">
	<div id="comments">
<?php	pr($comments); ?>
	</div>
</div>