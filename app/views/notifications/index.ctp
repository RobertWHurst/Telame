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
$this->set('title_for_layout', __('notifications', true));
?>
<div id="page_head" class="clearfix">
	<h1 class="page_title"><?php echo __('notifications', true); ?></h1>
</div>
<div id="page_body" class="clearfix">
	<div id="notifications">
<?php
	foreach($notifications as $n) {
		echo $n['Notification']['content'] . '<br />';
		echo $n['User']['full_name'] . __('friend_added_you', true) . '<br />';
		if ($n['Notification']['new']) {
			echo $html->link(__('friend_confirm', true), '/f/a/' . $n['User']['id'] . '/true/' . $n['Notification']['id']);
		}
	}

	pr($notifications);
?>
	</div>
</div>