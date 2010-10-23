<?php
App::import('Sanitize');
class AppController extends Controller {

	//add user athentication
	// autologin must be before auth in array
	// Cookie, Auth, and AuthExtension MUST BE IN THIS ORDER TO WORK PROPERLY
	var $components = array('Aacl', 'Cookie', 'Auth', 'AuthExtension', 'RequestHandler', 'Security', 'Session');

	var $helpers = array('Form', 'Html', 'Session', 'Asset.asset', 'HtmlImage');

	// Current user's info stored here
	var $currentUser;

	// Not for use when developing
//	var $persistModel = true;

	function beforeFilter() {
		$this->Cookie->path = '/';
		$this->Cookie->domain = env('HTTP_BASE');

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
			$this->loadModel('User');
			// The currently logged in user's infomration
			$this->currentUser = $this->getCurrentUser();

			// send people not admin's to
			if (env('SERVER_NAME') != 'www.telame.com' && $this->currentUser['User']['level'] > 0) {
				$this->redirect('http://www.telame.com');
			}
			// ensure the user has a profile
			$this->checkProfile();
		}
	}

	function beforeRender() {
	}

	function checkProfile() {
		if ($this->currentUser['User']['first_login']) {
			$this->Session->setFlash(__('new_user_welcome', true));
			if ($this->here == '/' . $this->currentUser['User']['slug'] || $this->here == '/') {
				$this->redirect(array('slug' => $this->currentUser['User']['slug'], 'controller' => 'settings', 'action' => 'basic'));
				exit;
			}
		}
	}

	function getCurrentUser() {
		$currentUser = $this->User->getProfile($this->Session->read('Auth.User.slug'));

		if(!is_null($currentUser['User']['hash'])) {
			$this->AuthExtension->logout();
			$this->Auth->logout();
			$this->Session->destroy();
			$this->Session->setFlash(__('email_not_confirmed', true), 'default', array('class' => 'error'));
			$this->redirect('/c/' . $currentUser['User']['email']);
			exit;
		}
		$this->set('currentUser', $currentUser);
		return $currentUser;
	}

	function _forceSSL() {
		$this->redirect('https://' . env('SERVER_NAME') . $this->here);
	}

	function _unforceSSL() {
		$this->redirect('http://' . env('SERVER_NAME') . $this->here);
	}

}