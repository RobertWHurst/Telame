<div id="navigation">
<?php
	echo $html->link(__('Home', true), '/');
	echo $html->link(__('Features', true), '/pages/features');
	// If the user is not logged in, display the signup and login links
	if (!Configure::read('LoggedIn', true)) {
		echo $html->link(__('Signup', true), '/#signup');
		echo $html->link(__('Login', true), '/users/login');
	} else {
		echo $html->link(__('Your Profile', true), '/profiles/profile');
		echo $html->link(__('Logout', true), '/users/logout');
	}
?>
</div>