<?php
//INCLUDES
$this->set('css_for_layout', array(
	'base',
	'tall_header',
	'main_sidebar',
	'messages/messages',
	'messages/sent',
	'messages/summary'
));
$this->set('script_for_layout', array(
	'jquery',
	'base',
	'header_glow',
	'main_sidebar',	
	'messages/messages'
));
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