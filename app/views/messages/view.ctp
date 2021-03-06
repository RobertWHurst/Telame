<?php
//INCLUDES
$html->css(array(
	'base',
	'tall_header',
	'main_sidebar',
	'messages/messages',
	'messages/view'
), null, array('inline' => false));
$js = array(
	'jquery',
	'base',
	'main_sidebar'
);
foreach ($js as $j) {
	$javascript->link($j, false);
}
$this->set('title_for_layout', __('message_view', true));

?>
<div id="page_head" class="clearfix">
	<h1 class="page_title"><?php echo __('messages', true); ?></h1>
</div>
<div id="page_navigation" class="clearfix">
	<?php
		$nav_links = array(
			array(
				'label' => __('message_thread_title', true),
				'url' => array(
					'slug' => $currentUser['User']['slug'],
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
			//findout if the thread belongs to the current user
			if($message['Author']['id'] == $currentUser['User']['id']){
				$is_user_thread = true;
				$composer['user_id'] = $message['User']['id'];
			}
			else{
				$is_user_thread = false;
				$composer['user_id'] = $message['Author']['id'];
			}

			//if this message is the parent
			if($message['Message']['parent_id'] < 0){

				//figure out the user_id and author_id for a response
				$composer['parent_id'] = $message['Message']['id'];
				$composer['author_id'] = $currentUser['User']['id'];

			}

			if($message['Message']['subject']):
?>
				<div class="message head clearfix">
			<?php else: ?>
				<div class="message clearfix">
			<?php endif; ?>
				<div class="avatar">
<?php
					$image_url = array('controller' => 'media', 'action' => 'avatar', $message['Author']['avatar_id']);
					$url = array('controller' => 'messages', 'action' => 'view', $message['Message']['id']);
					echo $this->Html->image($image_url, array('url' => $url));
?>
				</div>
				<div class="message_content">
					<?php if($message['Message']['subject']): ?>
						<?php if($is_user_thread): ?>
							<h2 class="to"><?php echo __('message_to', true) . ' ' . $message['User']['full_name']; ?></h2>
						<?php else: ?>
							<h2 class="from"><?php echo __('message_from', true) . ' ' . $message['Author']['full_name']; ?></h2>
						<?php endif; ?>
						<h1 class="subject"><?php echo $message['Message']['subject']; ?></h1>
					<?php endif; ?>
					<?php echo $markdown->parse($message['Message']['content']); ?>
				</div>
				<div class="time">
					<p>
<?php
				echo __('composed') . ' ' . $time->timeAgoInWords($message['Message']['created']);
				if($message['Message']['read']) {
					echo '<br />';
					echo __('read') . ' ' . $time->timeAgoInWords($message['Message']['read']);
				}
?>
					</p>
				</div>
			</div>
		<?php endforeach; ?>
		<div id="composer">
<?php
			echo $form->create('Message', array('url' =>  array('controller' => 'messages', 'action' => 'send_message')));
			echo $form->hidden('user_id', array('value' => $composer['user_id']));
			echo $form->hidden('author_id', array('value' => $composer['author_id']));
			echo $form->hidden('parent_id', array('value' => $composer['parent_id']));
			echo $form->hidden('subject', array('value' => null));
			echo $form->textarea('content');
			echo $form->end('Post Reply');
?>
		</div>
	</div>
</div>