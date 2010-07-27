<div id="navigation">
<?php
	echo $html->link(__('Home'), '/');
	echo $html->link(__('Features'), '/pages/features');
	// If the user is not logged in, display the signup and login links
	if (!Configure::read('LoggedIn')) {
		echo $html->link(__('Signup'), '/#signup');
		echo $html->link(__('Login'), '/users/login');
	} else {
		echo $html->link(__('Your Profile'), '/profiles/profile');
		echo $html->link(__('Logout'), '/users/logout');
	}
?>
</div>