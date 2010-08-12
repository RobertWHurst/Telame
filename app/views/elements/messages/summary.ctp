<?php	
//figure out if the message is a response or a thread
if($message['Message']['parent_id'] > 0)
	$threadMessage = 'ParentMessage';
else
	$threadMessage = 'Message';		
?>
<div class="message clearfix">
	<div class="avatar">
<?php
		$image_url = array('controller' => 'media', 'action' => 'avatar', $message['Author']['id']);
		$url = array('controller' => 'users', 'action' => 'profile', $message['Author']['slug']);
		echo $this->Html->image($image_url, array('url' => $url, 'width' => '60', 'height' => '60'));
?>
	</div>
	<div class="message_content">
<?php
		$link_content = "<h1 class=\"subject\">{$message[$threadMessage]['subject']}</h1>";
		$link_content .= "<h2 class=\"from\">{$message['Author']['Profile']['full_name']}</h2>";
		echo $html->link($link_content, array('controller' => 'messages', 'action' => 'view', $message[$threadMessage]['id']), array('escape' => false));
?>
	</div>
	<div class="time"><p><?php echo $message['Message']['created']; ?></p></div>
</div>