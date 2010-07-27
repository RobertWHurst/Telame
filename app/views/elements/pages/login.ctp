<div id="login">
<?php 
	echo $form->create('loginForm');
	echo $form->input('email');
	echo $form->input('password');
	echo $form->submit();
	echo $form->end();
?>
</div>