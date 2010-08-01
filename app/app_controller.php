<?php
class AppController extends Controller {

	//add user athentication
	var $components = array('Auth', 'Session', 'Includer');
	var $currentUser;

	// Not for use when developing
//	var $persistModel = true; 

	function beforeFilter() {

		if(Configure::read('debug') > 0){
			//load krumo
			App::import('Vendor', 'krumo', array('file' => 'krumo/class.krumo.php'));
		}
		
		$this->Auth->fields = array('username' => 'email', 'password' => 'password');

		// get the user's info and store it in the 'user' var
		$user = $this->Session->read('Auth');
		
		//redirect to the user's profile.
		$this->Auth->loginRedirect = array('controller' => 'users', 'action' => 'redirect_login');
		
		//redirect home after logout
		$this->Auth->logoutRedirect = array(Configure::read('Routing.admin') => false, 'controller' => 'pages', 'action' => 'home');

		// Read the user id from the session and if nothing there, set it to 1
		Configure::write('UID', (!isset($user['User']['id']) ? '0' : $user['User']['id']));
		Configure::write('LoggedIn', $this->Session->check('Auth.User.email'));
		
		if (Configure::read('LoggedIn')) {
			// The currently logged in user's infomration
			$this->currentUser = Classregistry::init('User');
			$this->currentUser = $this->currentUser->findById(Configure::read('UID'));
			$this->set('currentUser', $this->currentUser);
		}
		
		$this->Auth->allow('rootRedirect');
		
	}

	function beforeRender() {
	}
	
	function rootRedirect(){
		if($this->currentUser){
			$this->redirect(array('controller' => 'users', 'action' => 'profile', $this->currentUser['User']['slug']));
		}
		else{
			$this->redirect(array('controller' => 'pages', 'action' => 'signup'));		
		}
	}
}