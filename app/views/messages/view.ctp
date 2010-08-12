<?php
//INCLUDES
$this->set('css_for_layout', array(
	'base',
	'tall_header',
	'main_sidebar',
	'messages/view'
));
$this->set('script_for_layout', array(
	'jquery',
	'base',
	'main_sidebar'
));
?>
<div id="content" class="clearfix">
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
			<?php
				$nav_links = array(
					array(
						'label' => __('message_thread_title', true),
						'url' => array(
							'controller' => 'messages',
							'action' => 'view',
							$this->params['pass'][0]
						),
						'classes' => 'button'
					)
				);
				echo $this->element('messages/navigation', compact('nav_links'));
			?>
		</div>
		<div id="page_body" class="clearfix">
			<div id="messages">
<?php 
				foreach($messages as $message):
				
					//figure out if the message is threaded
					if($message['Message']['subject'])
						$title = $thread_title = $message['Message']['subject'];
					else
						$title = "RE: {$thread_title}";
		
				//figure out if the message is a responce or a thread
				if($message['Message']['parent_id'] > 0)
					$threadMessage = 'ParentMessage';
				else
					$threadMessage = 'Message';
			
?>
					<div class="message">
						<div class="avatar">
<?php
							$image_url = array('controller' => 'media', 'action' => 'avatar', $message['Author']['id']);
							$url = array('controller' => 'messages', 'action' => 'view', $message['Message']['id']);
							echo $this->Html->image($image_url, array('url' => $url, 'width' => '60', 'height' => '60'));
?>
						</div>
						<h1 class="subject"><?php echo $title; ?></h1>
						<h2 class="from"><?php echo $message['Author']['Profile']['full_name']; ?></h2>
						<div class="date"><p><?php echo $message['Message']['created']; ?></p></div>
						<div class="message_content">
							<?php echo $message['Message']['content']; ?>
						</div>
					</div>
				<?php endforeach; ?>
			</div>
		</div>
	</div>
</div>
<?php echo $this->element('copyright'); ?>