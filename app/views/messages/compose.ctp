<?php
//INCLUDES
$this->set('css_for_layout', array(
	'base',
	'tall_header',
	'main_sidebar',
	'messages/messages',
	'messages/compose'
));
$this->set('script_for_layout', array(
	'jquery',
	'base', 
	'main_sidebar'
));
?>
<div id="page_head" class="clearfix">			
	<h1 class="page_title"><?php echo __('messages', true); ?></h1>
</div>
<div id="page_navigation" class="clearfix">
	<?php echo $this->element('messages/navigation'); ?>
</div>
<div id="page_body" class="clearfix">
	<div id="composer">
<?php
		echo $form->create('Message', array('url' =>  array('controller' => 'messages', 'action' => 'send_message')));
		echo $form->hidden('author_id', array('value' => $currentUser['User']['id']));
		echo $form->hidden('parent_id', array('value' => -1));
		echo $form->input('user_id', array('type' => 'text', 'label' => 'user_id'));
		echo $form->input('subject', array('type' => 'text', 'label' => __('message_subject', true)));
		echo $form->textarea('content');
		echo $form->end('Post Reply');
?>
	</div>
</div>