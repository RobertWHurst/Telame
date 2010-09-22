<?php 
	echo $form->create('Event', array('target' => '_parent') ); 
	echo $form->input('id', array('type' => 'hidden', 'value' => $event['Event']['id']));
	echo $form->input('title', array('value' => $event['Event']['title']));
	echo $form->hidden('start', array('value' => $event['Event']['start']));
	echo 'When: ' . $displayTime; ?>
	<a href="<?php echo $currentUser['User']['slug'] ?>/calendar/delete/<?php echo $event['Event']['id'];?>" onClick="return confirm('Do you really want to delete this event?');">Delete</a> 
	<?php echo $form->end(array('label' => 'Save' )); 
	//Below is just a cancel button. See previous post for the back() function ?>
	<input class="nicebutton" type="button" value="Cancel" onClick="back();">