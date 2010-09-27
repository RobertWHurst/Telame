<?php
	echo $form->create('Event', array('target' => '_parent') );
	echo $form->input('id', array('type' => 'hidden', 'value' => $event['Event']['id']));
	echo $form->input('title', array('value' => $event['Event']['title']));
	echo $form->hidden('start', array('value' => $event['Event']['start']));
	echo 'When: ' . $displayTime;
	echo $html->link(
		__('event_delete', true),
		array(
			'slug' => $currentUser['User']['slug'],
			'controller' => 'events',
			'action' => 'delete',
			$event['Event']['id'],
		),
		null,
		__('event_delete_confirm', true)
	);
	echo $form->end(array('label' => 'Save' ));
	//Below is just a cancel button. See previous post for the back() function ?>
	<input class="nicebutton" type="button" value="Cancel" onClick="back();">