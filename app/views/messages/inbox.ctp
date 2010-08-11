<?php krumo($messages); ?>

<div id="content">
	<div id="messages">
		<?php foreach($messages as $message): ?>
			<div class="message">
				<div class="avatar">
<?php
					$image_url = array('controller' => 'media', 'action' => 'avatar', $message['Author']['id']);
					$url = array('controller' => 'messages', 'action' => 'view', $message['Message']['id']);
					echo $this->Html->image($image_url, array('url' => $url, 'width' => '60', 'height' => '60'));
?>
				</div>
<?php
					$link_content = "<h1 class=\"subject\">{$message['Message']['subject']}</h1>";
					$link_content .= "<h2 class=\"from\">{$message['Author']['Profile']['full_name']}</h2>";
					echo $html->link($link_content, array('controller' => 'messages', 'action' => 'view', $message['Message']['id']), array('escape' => false));
?>
				<div class="date"><p><?php echo $message['Message']['created']; ?></p></div>
			</div>
		<?php endforeach; ?>
	</div>
</div>