<?php
class AppController extends Controller {

	//add user athentication
	var $components = array('Acl', 'Auth', 'Session', 'Includer');
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
		$this->Auth->loginRedirect = array('controller' => 'notifications', 'action' => 'news');
		
		//redirect home after logout
		$this->Auth->logoutRedirect = array(Configure::read('Routing.admin') => false, 'controller' => 'pages', 'action' => 'signup');

		// Read the user id from the session and if nothing there, set it to 1
		Configure::write('UID', (!isset($user['User']['id']) ? '0' : $user['User']['id']));
		Configure::write('LoggedIn', $this->Session->check('Auth.User.email'));
		
		// This is available everywhere, be careful what you include, we don't want excessive info
		if (Configure::read('LoggedIn')) {
			// The currently logged in user's infomration
			$this->currentUser = Classregistry::init('User');
			$this->currentUser->Behaviors->attach('Containable');
			$this->currentUser = $this->currentUser->find('first', array(
						'conditions' => array(
							'id' => Configure::read('UID'),
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
				
			$this->set('currentUser', $this->currentUser);
		}		
	}

	function beforeRender() {
	}
	
}