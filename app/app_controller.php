<?php
App::import('Sanitize');
class AppController extends Controller {

	//add user athentication
	// autologin must be before auth in array
	// Cookie, Auth, and AuthExtension MUST BE IN THIS ORDER TO WORK PROPERLY
	var $components = array('Aacl', 'Cookie', 'Auth', 'AuthExtension', 'RequestHandler', 'Security', 'Session');

	// Current user's info stored here
	var $currentUser;

	// Not for use when developing
//	var $persistModel = true;

	function beforeFilter() {
		// must be here for auto login to work
		$this->Auth->autoRedirect = false;
		$this->Auth->fields = array('username' => 'email', 'password' => 'password');
		//redirect to the user's news feed.
		$this->Auth->loginRedirect = array('controller' => 'notifications', 'action' => 'news');
		//redirect home after logout
		$this->Auth->logoutRedirect = array(Configure::read('Routing.admin') => false, 'controller' => 'pages', 'action' => 'home');

		//LOAD VENDORS
		if(Configure::read('debug') > 0){
			//load krumo
			App::import('Vendor', 'krumo', array('file' => 'krumo/class.krumo.php'));
		}

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
						'User.id' => $this->Session->read('Auth.User.id'),
					),
					'contain' => array(
						'Notification' => array(
							'conditions' => array(
								'new' => true,
							)
						),
						'Profile'
					)
				)
			);
		if(!is_null($currentUser['User']['hash'])) {
			$this->AutoLogin->delete();
			$this->Session->destroy();
			$this->Session->setFlash(__('email_not_confirmed', true));
			$this->redirect('/c/' . $currentUser['User']['email']);
		}
		$this->set('currentUser', $currentUser);
		return $currentUser;
	}

}