<?php
class AppController extends Controller {

	//add user athentication
//	var $components = array('Auth', 'AutoLogin', 'Session', 'Includer');
	var $components = array('Auth', 'Session', 'Includer');
	var $currentUser;

	// Not for use when developing
//	var $persistModel = true; 

	function beforeFilter() {

		if(Configure::read('debug') > 0){
			//load krumo
			App::import('Vendor', 'krumo', array('file' => 'krumo/class.krumo.php'));
			
		}
		
		//force athenication against profiles
		$this->Auth->fields = array('username' => 'email', 'password' => 'password');
		
		// login/logout variables
		//$this->AutoLogin->expires = '+1 month';

		// redirect after login to profile, and home after logout
		$this->Auth->logoutRedirect = array(Configure::read('Routing.admin') => false, 'controller' => 'pages', 'action' => 'home');
		$this->Auth->loginRedirect = array('controller' => 'users', 'action' => 'profile');

		// get the user's info and store it in the 'user' var
		$user = $this->Session->read('Auth');

		// Read the user id from the session and if nothing there, set it to 1
		Configure::write('UID', (!isset($user['User']['id']) ? '0' : $user['User']['id']));
		Configure::write('LoggedIn', $this->Session->check('Auth.User.email'));
		
		if (Configure::read('LoggedIn')) {
			// The currently logged in user's infomration
			$this->currentUser = Classregistry::init('User');
			$this->currentUser = $this->currentUser->findById(Configure::read('UID'));
			$this->set('currentUser', $this->currentUser);
		}
	}

	function beforeRender() {
	}

}