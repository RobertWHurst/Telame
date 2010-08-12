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
	<div class="avatar">
<?php
		$iUrl = array('controller' => 'media', 'action' => 'avatar', $message['Author']['id']);
		$aUrl = array('controller' => 'users', 'action' => 'profile', $message['Author']['slug']);
		echo $this->Html->image($iUrl, array('url' => $aUrl, 'width' => '60', 'height' => '60'));
?>
	</div>
	<div class="message_content">
		<h1 class="subject"><?php echo __('message_subject', true) . ' ' . $html->link($tSubject, $mUrl); ?></h1>
		<p>
			<?php echo __('message_from', true) . ' ' . $html->link($message['Author']['Profile']['full_name'], $aUrl); ?> &mdash; <?php echo $message['Message']['content']; ?>
		</p>
	</div>
	<div class="time"><p><?php echo $message['Message']['created']; ?></p></div>
</div>