<div id="content">
<?php 
	echo $form->create();
	echo $form->input('email');
	echo $form->input('slug');
	echo $form->input('password');
	echo $form->input('passwd');
	echo $form->end(__('signup', true));
	
	echo $this->element('copyright'); 
?>	
</div>