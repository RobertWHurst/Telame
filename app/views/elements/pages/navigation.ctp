<div id="navigation">
<?php
	echo $html->link(__('Features', true), array('controller' => 'pages', 'action' => 'features'));
	// If the user is not logged in, display the signup and login links
	if (!Configure::read('LoggedIn', true)) {
		echo $html->link(__('Signup', true), array('controller' => 'pages', 'action' => 'signup'));
		echo $html->link(__('Login', true), array('controller' => 'users', 'action' => 'login'));
	}
	else{
		echo $html->link(__('Your Profile', true), array('controller' => 'users', 'action' => 'profile', $currentUser['User']['slug']));
		echo $html->link(__('Logout', true), array('controller' => 'users', 'action' => 'logout'));
	}
?>
</div>