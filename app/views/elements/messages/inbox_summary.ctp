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

//figure out if the message is read
if($message['Message']['read'] != null){
	$status = $html->image('icons/email_open.png');
	$classes = 'message clearfix read';
}
else{
	$status = $html->image('icons/email.png');
	$classes = 'message clearfix unread';

}
	
//save the message url array
$mUrl = array('controller' => 'messages', 'action' => 'view', $tid);
?>
<div class="<?php echo $classes; ?>">
	<div class="status">
		<?php echo $status; ?>
	</div>
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
	<div class="time"><p><?php echo $time->timeAgoInWords($message['Message']['created']); ?></p></div>
</div>