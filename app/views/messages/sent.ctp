<?php
//INCLUDES
$html->css(array(
	'base',
	'tall_header',
	'main_sidebar',
	'messages/messages',
	'messages/sent',
	'messages/summary'
), null, array('inline' => false));
$js = array(
	'jquery',
	'base',
	'main_sidebar',
	'messages/messages'
);
foreach ($js as $j) {
	$javascript->link($j, false);
}
$this->set('title_for_layout', __('messages_sent', true));

?>
<div id="page_head" class="clearfix">
	<h1 class="page_title"><?php echo __('messages', true); ?></h1>
</div>
<div id="page_navigation" class="clearfix">
	<?php echo $this->element('messages/navigation'); ?>
</div>
<div id="page_body" class="clearfix">
	<div id="messages">
<?php
		if(is_array($messages)):
			foreach($messages as $message)
				echo $this->element('messages/sent_summary', compact('message'));
		else:
?>
			<p class="empty">You haven't sent any messages.</p>
		<?php endif; ?>
	</div>
</div>