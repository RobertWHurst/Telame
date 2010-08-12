<?php	
//figure out if the message is a response or a thread
if($message['Message']['parent_id'] > 0)
	$threadMessage = 'ParentMessage';
else
	$threadMessage = 'Message';		

//save the message url array
$mUrl = array('controller' => 'messages', 'action' => 'view', $message[$threadMessage]['id']);
?>
<div class="message clearfix">
	<div class="avatar">
<?php
		$iUrl = array('controller' => 'media', 'action' => 'avatar', $message['User']['id']);
		$uUrl = array('controller' => 'users', 'action' => 'profile', $message['User']['slug']);
		echo $this->Html->image($iUrl, array('url' => $uUrl, 'width' => '60', 'height' => '60'));
?>
	</div>
	<div class="message_content">
		<h1 class="to"><?php echo __('message_to', true) . ' ' . $html->link($message['User']['Profile']['full_name'], $uUrl); ?></h1>
		<h2 class=\"subject\"><?php echo __('message_subject', true) . ' ' . $html->link($message[$threadMessage]['subject'], $mUrl); ?></h2>
	</div>
	<div class="time"><p><?php echo $message['Message']['created']; ?></p></div>
</div>