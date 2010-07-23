<div id="login">
<?php 
	echo $form->create('loginForm', array(
		'url' => $html->url('/profiles/login', true)
	));
	echo $form->input('email');
	echo $form->input('password');
	echo $form->submit();
	echo $form->end();
?>
</div>