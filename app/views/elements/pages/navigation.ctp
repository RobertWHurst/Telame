<div id="navigation">
<?php
	echo $html->link(__('Home', true), '/');
	echo $html->link(__('Features', true), '/features');
	// If the user is not logged in, display the signup and login links
	if (!Configure::read('LoggedIn', true)) {
		echo $html->link(__('Signup', true), '/#signup');
		echo $html->link(__('Login', true), '/login');
	} else {
		echo $html->link(__('Your Profile', true), '/' . $currentUser['User']['slug']);
		echo $html->link(__('Logout', true), '/logout');
	}
?>
</div>