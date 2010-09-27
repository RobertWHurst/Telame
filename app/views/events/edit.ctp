<?php
	echo $form->create('Event', array('target' => '_parent') );
	echo $form->input('id', array('type' => 'hidden', 'value' => $event['Event']['id']));
	echo $form->input('title', array('value' => $event['Event']['title']));
	echo $form->input('start', array('value'=>$event['Event']['start']));
	echo $form->input('end', array('value'=>$event['Event']['end']));
	echo $form->input('allday', array('value'=>$event['Event']['allday']));
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
	echo $form->end(__('save', true));
	//Below is just a cancel button. See previous post for the back() function ?>
	<input class="nicebutton" type="button" value="Cancel" onClick="back();">