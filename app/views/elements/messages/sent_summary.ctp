<?php	
//figure out if the message is a response or a thread
if($message['Message']['parent_id'] > 0){
	$tid = $message['ParentMessage']['id'];
	$tSubject = $message['ParentMessage']['subject'];
}
else{
	$tid = $message['Message']['id'];
	$tSubject = $message['Message']['subject'];
}

//save the message url array
$mUrl = array('controller' => 'messages', 'action' => 'view', $tid);
?>
<div class="message clearfix">
	<div class="status">
<?php
		if($message['Message']['read'])
			echo $html->image('icons/email_open.png');
		else
			echo $html->image('icons/email.png');								
?>
	</div>
	<div class="check">
		<?php echo $form->checkbox("selected[{$message['Message']['id']}]"); ?>
	</div>
	<div class="avatar">
<?php
		$iUrl = array('controller' => 'media', 'action' => 'avatar', $message['User']['id']);
		$uUrl = array('controller' => 'users', 'action' => 'profile', $message['User']['slug']);
		echo $this->Html->image($iUrl, array('url' => $uUrl, 'width' => '60', 'height' => '60'));
?>
	</div>
	<div class="message_content">
		<h1 class="subject"><?php echo __('message_subject', true) . ' ' . $html->link($tSubject, $mUrl); ?></h1>
		<p>
			<?php echo __('message_to', true) . ' ' . $html->link($message['User']['Profile']['full_name'], $iUrl); ?> - <?php echo $message['Message']['content']; ?>
		</p>
	</div>
	<div class="time"><p><?php echo $time->timeAgoInWords($message['Message']['created']); ?></p></div>
</div>