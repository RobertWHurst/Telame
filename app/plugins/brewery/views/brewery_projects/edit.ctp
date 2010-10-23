<?php
	echo $form->create();
	echo $form->input('summary');
	echo $form->input('description');
	echo $form->input('id', array('type' => 'hidden'));
	echo $form->end(__('save', true));
?>