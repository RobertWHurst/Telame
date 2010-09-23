<?php

class SlugRoute extends CakeRoute {

	function parse($url) {
		$params = parent::parse($url);
		if (empty($params)) {
			return false;
		}
		App::import('Component', 'Session');
		$Session = new SessionComponent();
		if($Session->check('Auth.User.slug')) {
			return $params;
		}
		return false;
	}
}

?>