<?php
	krumo($messages);

?>
<div id="content">
	<div id="messages">
<?php 
		foreach($messages as $message):
			
			//figure out if the message is threaded
			if($message['Message']['subject'])
				$title = $thread_title = $message['Message']['subject'];
			else
				$title = "RE: {$thread_title}";
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