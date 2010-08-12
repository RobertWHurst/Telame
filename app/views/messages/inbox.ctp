<?php
//INCLUDES
$this->set('css_for_layout', array(
	'base',
	'tall_header',
	'main_sidebar',
	'messages/inbox',
	'messages/summary'
));
$this->set('script_for_layout', array(
	'jquery',
	'base',
	'main_sidebar'
));
?>
<div id="content">
	<div id="wrap_main_sidebar">
		<div id="logo">
			<!-- TODO: link to the news feed -->
			<?php echo $html->image('logo.png', array('title' => __('site_name', true),'url' => array('controller' => 'notifications', 'action' => 'news'))); ?>
		</div>
		<?php echo $this->element('main_sidebar'); ?>
	</div>
	<div id="page">
		<div id="page_head" class="clearfix">			
			<h1 class="page_title"><?php echo __('messages_inbox_title', true); ?></h1>
		</div>
		<div id="page_navigation" class="clearfix">
			<?php echo $this->element('messages/navigation'); ?>
		</div>
		<div id="page_body" class="clearfix">
			<div id="messages">
<?php 
				foreach($messages as $message){
					echo $this->element('messages/summary', compact('message'));
				} 
?>
			</div>
		</div>
	</div>
</div>
<?php echo $this->element('copyright'); ?>