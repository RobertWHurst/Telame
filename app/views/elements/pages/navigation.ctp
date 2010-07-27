<div id="navigation">
<?php
	echo $html->link('Home', '/');
	echo $html->link('Features', '/pages/features');
	// If the user is not logged in, display the signup and login links
	if (!Configure::read('LoggedIn')) {
		echo $html->link('Signup', '/#signup');
		echo $html->link('Login', '/users/login'); 
	} else {
		echo 'Welcome Back';
	}
?>
</div>