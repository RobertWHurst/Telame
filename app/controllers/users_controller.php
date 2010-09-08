<?php
class UsersController extends AppController {

	var $components = array('Email');
	var $helpers = array('Markdown', 'Time');

	function beforeFilter(){
		parent::beforeFilter();

		$this->Security->blackHoleCallback = '_forceSSL';
		$this->Security->requireSecure('login', 'signup');
		if (!in_array($this->action, $this->Security->requireSecure) && env('HTTPS')) {
		 	$this->_unforceSSL();
		}
		$this->Auth->allow(array('confirm', 'signup'));
	}

	function confirm($email = null, $hash = null) {
		if (!is_null($email) && !is_null($hash) || !empty($this->data)) {
			if (!empty($this->data)) {
				$email = $this->data['User']['email'];
				$hash = $this->data['User']['hash'];
			}
			if ($this->User->confirm($email, $hash)) {
				$this->Session->setFlash(__('email_confirmed', true));
			} else {
				$this->Session->setFlash(__('email_or_hash_failed', true));
			}
			$this->redirect('/');
			exit;
		} else {
			$this->data['User']['email'] = $email;
			$this->data['User']['hash'] = $hash;
		}
	}

	function profile($slug){
		//set the layout
		$this->layout = 'profile';

		$canView = false;

		// get the user's info based on their slug
		$user = $this->User->getProfile($slug);

		if(!$user){
			$this->redirect(array('controller' => 'users', 'action' => 'profile', $this->currentUser['User']['slug']));
			exit;
		}

		// check if the requested user is yourself
		if ($this->currentUser['User']['id'] != $user['User']['id']) {

			// Do permission check
			if($this->Aacl->checkPermissions($user['User']['id'], $this->currentUser['User']['id'], 'profile')) {
				$canView = true;
			} else {
				$this->Session->setFlash(__('not_allowed_profile', true));
			}
			// are you friends with this person
			$isFriend = ($this->User->GroupsUser->isFriend($this->currentUser['User']['id'], $user['User']['id']) ? true : false);
		} else {
			// These are defaults for viewing your own profile
			$canView = true;
			$isFriend = true;
		}

		if ($canView) {
			$friends = $this->User->GroupsUser->getFriends(array('uid' => $user['User']['id'], 'random' => true, 'limit' => 10));
			$wallPosts = $this->User->WallPost->getWallPosts(10, 0, array('uid' => $user['User']['id']));
		} else {
			$friends = array();
			$wallPosts = array();
		}


		//pass the profile data to the view
		$this->set(compact('friends', 'isFriend', 'user', 'wallPosts'));
	}

	function search(){

		//TODO: these results will need to be ranked by relationship, network, secondary relationships, and location, in that order.

		//get all the searchable profiles
		$results = $this->User->findAllBySearchable(true);

		$this->set('results', $results);
	}

	function signup($key = null) {
		$this->layout = 'simple_header';
		// data has been posted
		if (!empty($this->data)) {
			// make sure the passwords match, if not show the page again with all the current info except the password
			if ($this->data['User']['password'] != $this->Auth->password($this->data['User']['passwd'])) {
				$this->Session->setFlash(__('password_mismatch', true));
				unset($this->data['User']['password']);
				unset($this->data['User']['passwd']);
			// passwords match
			} else {
				// import the beta keys model
				$this->loadModel('BetaKey');
				$key = $this->BetaKey->find('first', array('conditions' => array('key' => $this->data['User']['beta_key'])));
				if (!$key) {
					$this->Session->setFlash(__('invalid_key', true));
					$this->redirect($this->referer());
					exit;
				}

				// We need the hash here for the confirmation email
				$this->data['User']['hash'] =  sha1(date('Y-m-d') . $this->data['User']['email'] . Configure::read('Security.salt'));
				if ($this->User->signup($this->data)) {
					// send user email
					$this->Email->from		=	'Telame.com <admin@telame.com>';
					$this->Email->to		= $this->data['User']['slug'] . '<' . $this->data['User']['email'] . '>';
					$this->Email->subject	= 'Your ' . __('site_name', true) . ' account has been created.';
					$this->Email->sendAs	= 'both';
					$this->Email->template	= 'signup';
					$this->set('user', $this->data);
					$this->Email->send();

					// Delete the beta key here, that way it's the last thing to be done, if something else fails, they can try again
					$this->BetaKey->delete($key['BetaKey']['id']);

					// tell the user it's all good
					$this->Session->setFlash(__('user_saved', true));
				} else {
					$this->Session->setFlash(__('user_create_error'));
				}
				   $this->redirect('/');
				   exit;
			}
		} else {
			// add the beta key to the default $this->data array
			$this->data['User']['beta_key'] = $key;
		}
	}


//----------------- Important functions we don't need to see often ------------------//

	function _forceSSL() {
		$this->redirect('https://' . env('SERVER_NAME') . $this->here);
	}

	function _unforceSSL() {
		$this->redirect('http://' . env('SERVER_NAME') . $this->here);
	}

	function login(){
		$this->layout = 'tall_header';
		$this->AuthExtension->checkRememberMe();
	}

	/** delegate /users/logout request to Auth->logout method */
	function logout(){
		$this->AuthExtension->logout();
		$this->Auth->logout();
		$this->redirect('/');
	}

}