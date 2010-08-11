<?php krumo($messages); ?>

<div id="content">
	<div id="messages">
		<?php foreach($messages as $message): ?>
			<div class="message">
				<div class="avatar">
<?php
					$image_url = array('controller' => 'media', 'action' => 'avatar', $message['Author']['id']);
					$url = array('controller' => 'users', 'action' => 'profile', $message['Author']['slug']);
					echo $this->Html->image($image_url, array('url' => $url, 'width' => '60', 'height' => '60'));
?>
				</div>
				<h1 class="subject"><?php echo $message['Message']['subject']; ?></h1>
				<h2 class="from"><?php echo $message['Author']['Profile']['full_name']; ?></h2>
				<div class="date"><p><?php echo $message['Message']['created']; ?></p></div>
			</div>
		<?php endforeach; ?>
	</div>
</div>