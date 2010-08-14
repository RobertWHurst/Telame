<?php
App::import('Sanitize');
class AppController extends Controller {

	//add user athentication
	// autologin must be before auth in array
	var $components = array('Aacl', 'AutoLogin', 'Auth', 'RequestHandler', 'Security', 'Session');

	// Current user's info stored here
	var $currentUser;

	// Not for use when developing
//	var $persistModel = true;

	function beforeFilter() {
		//LOAD VENDORS
		if(Configure::read('debug') > 0){
			//load krumo
			App::import('Vendor', 'krumo', array('file' => 'krumo/class.krumo.php'));
		}

		$this->Auth->fields = array('username' => 'email', 'password' => 'password');

		//redirect to the user's news feed.
        $this->Auth->loginRedirect = array('/');

		//redirect home after logout
		$this->Auth->logoutRedirect = array(Configure::read('Routing.admin') => false, 'controller' => 'pages', 'action' => 'home');

		// Write a config for if a user is logged in
		Configure::write('LoggedIn', $this->Session->check('Auth.User.email'));

		// This is available everywhere, be careful what you include, we don't want excessive info
		if (Configure::read('LoggedIn')) {
			// The currently logged in user's infomration
			$this->currentUser = $this->getCurrentUser();
		}
	}

	function beforeRender() {
	}

	function getCurrentUser() {
		$this->loadModel('User');
		$this->User->Behaviors->attach('Containable');
		$currentUser = $this->User->find('first', array(
					'conditions' => array(
						'id' => $this->Session->read('Auth.User.id'),
					),
					'contain' => array(
						'Notification' => array(
							'conditions' => array(
								'new' => true,
							)
						)
					)
				)
			);
		$this->set('currentUser', $currentUser);
		return $currentUser;
	}

}