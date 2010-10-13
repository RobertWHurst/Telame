<?php
//INCLUDES
$html->css(array(
	'base',
	'tall_header',
	'main_sidebar',
	'messages/messages',
	'messages/compose'
), null, array('inline' => false));
$js = array(
	'jquery',
	'base', 
	'main_sidebar',
	'messages/compose'
);
foreach ($js as $j) {
	$javascript->link($j, false);
}
$this->set('title_for_layout', __('message_compose', true));

?>
<div id="page_head" class="clearfix">			
	<h1 class="page_title"><?php echo __('messages', true); ?></h1>
</div>
<div id="page_navigation" class="clearfix">
	<?php echo $this->element('messages/navigation'); ?>
</div>
<div id="page_body" class="clearfix">
	<div id="composer">
		<div id="create_message_wrap">
		<div class="create_message">
<?php
			echo $form->create('Message', array('url' =>  array('controller' => 'messages', 'action' => 'send_message')));
				
			//the hidden ids
			echo $form->hidden('author_id', array('value' => $currentUser['User']['id']));
			echo $form->hidden('parent_id', array('value' => -1));
?>
			<div class="recipients">
				<?php echo $form->input('user_slugs', array('type' => 'text', 'label' => __('recipients', true))); ?>
			</div>
			<div class="subject">
				<?php echo $form->input('subject', array('type' => 'text', 'label' => __('message_subject', true))); ?>
			</div>
			<div class="descriptTextarea">
				<?php echo $form->textarea('content'); ?>
			</div>
			<?php echo $form->end(__('send_message', true)); ?>
		</div>
	</div>
	<div id="composer_sidebar">
	
	</div>
</div>