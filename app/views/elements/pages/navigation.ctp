<div id="navigation">
<?php
	echo $html->link(__('features', true), array('controller' => 'pages', 'action' => 'features'));
	// If the user is not logged in, display the signup and login links
	if (!Configure::read('LoggedIn', true)) {
		echo $html->link(__('signup', true), array('controller' => 'pages', 'action' => 'signup'));
		echo $html->link(__('login', true), array('controller' => 'users', 'action' => 'login'));
	}
	else{
		echo $html->link(__('profile_title', true), array('controller' => 'users', 'action' => 'profile', $currentUser['User']['slug']));
		echo $html->link(__('logout', true), array('controller' => 'users', 'action' => 'logout'));
	}
?>
</div>