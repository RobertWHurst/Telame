<?php
	echo $form->create();
	echo $form->input('summary');
	echo $form->input('description');
	echo $form->end(__('add', true));
?>