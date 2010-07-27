<?php
class AppController extends Controller {

	//add user athentication
	var $components = array('Auth', 'Session');

	function beforeFilter() {
		//force athenication against profiles
		$this->Auth->fields = array('username' => 'email', 'password' => 'password');
		$user = $this->Session->read('Auth');
		// Read the user id from the session and if nothing there, set it to 1
		Configure::write('UID', (!isset($user['User']['id']) ? '1' : $user['User']['id']));
		Configure::write('LoggedIn', $this->Session->check('Auth.User.email'));

		if(Configure::read('debug') > 0){
			//load krumo
			App::import('Vendor', 'krumo', array('file' => 'krumo/class.krumo.php'));
		}
	}

	function beforeRender() {

	}

}