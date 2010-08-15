<?php
//INCLUDES
$this->set('css_for_layout', array(
	'base',
	'tall_header',
	'main_sidebar',
	'messages/messages',
	'messages/inbox',
	'messages/summary'
));
$this->set('script_for_layout', array(
	'jquery',
	'base',
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
		echo $form->create('Message', array('url' =>  array('controller' => 'messages', 'action' => 'manage_messages')));
			if(is_array($messages)):
				foreach($messages as $message)
					echo $this->element('messages/inbox_summary', compact('message'));
			else:
?>
				<p class="empty">Your Inbox is empty</p>
			<?php endif; ?>
			<div id="controls">
<?php
				echo $html->link('Mark selected as unread', array('controller' => 'messages', 'action' => 'inbox'), array('class' => 'unmark'));
				echo $html->link('Delete selected', array('controller' => 'messages', 'action' => 'inbox'), array('class' => 'delete'));
?>
			</div>
		<?php echo $form->end(); ?>
	</div>
</div>