<?php
class AppController extends Controller {

	//add user athentication
	// autologin must be before auth in array
	var $components = array('Acl', 'AutoLogin', 'Auth', 'Security', 'Session');
	var $currentUser;

	// Not for use when developing
//	var $persistModel = true;

	function beforeFilter() {
		$this->Security->blackHoleCallback = '_forceSSL';
		$this->Security->requireSecure('login');
		if (!in_array($this->action, $this->Security->requireSecure) and env('HTTPS')) {
		 	$this->_unforceSSL();
		}
		
		//LOAD VENDORS
		if(Configure::read('debug') > 0){
			//load krumo
			App::import('Vendor', 'krumo', array('file' => 'krumo/class.krumo.php'));
		}
		App::import('Vendor', 'php_markdown', array('file' => 'php_markdown/markdown.php'));

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

	function _forceSSL() {
		$this->redirect('https://' . env('SERVER_NAME') . $this->here);
	}

	function _unforceSSL() {
		$this->redirect('http://' . $_SERVER['SERVER_NAME'] . $this->here);
	}

}