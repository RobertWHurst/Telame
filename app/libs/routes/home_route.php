<?php

class HomeRoute extends CakeRoute {

	// function called by cake
    function parse($url) {
    	// import the session controller so we can check if they're logged in or not
		App::import('Component', 'Session');
		$Session = new SessionComponent();
		// check the login
		if($Session->check('Auth.User.email')) {
			// logged in, parse params and return
			return parent::parse($url);
    	} else {
    		// not logge in, return false
    		return false;
    	}
    }
 
}

?>