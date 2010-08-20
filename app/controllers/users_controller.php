<?php
class UsersController extends AppController {

	var $components = array('Email');
	var $helpers = array('Markdown', 'Text', 'Time');

	function beforeFilter(){
		parent::beforeFilter();

		$this->Security->blackHoleCallback = '_forceSSL';
		$this->Security->requireSecure('login');
		if (!in_array($this->action, $this->Security->requireSecure) && env('HTTPS')) {
		 	$this->_unforceSSL();
		}
		$this->Auth->allow(array('confirm', 'signup'));
//		$this->Auth->allow('signup');
	}

	function addFriend() {
		if (!empty($this->data)) {
			$this->User->GroupsUser->save($this->data);
		} else {
			$friendLists = $this->User->Group->getFriendLists(0, 0, array(
				'uid' => $this->currentUser['User']['id'],
				'type' => 'list',
				));
			$this->set(compact('friendLists'));
		}
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

	//A summary of whats new for the user.
	function index() {
		$wp = $this->User->WallPost->find('all');
	}

	function profile($slug){
		//set the layout
		$this->layout = 'profile';

		$canView = true;
		// get the user's info based on their slug
		$user = $this->User->getProfile($slug);

		if(!$user){
			$this->redirect(array('controller' => 'users', 'action' => 'profile', $this->currentUser['User']['slug']));
			exit;
		}

		// Do permission check
		if ($this->currentUser['User']['id'] != $user['User']['id']) {

			if(!$this->Aacl->checkPermissions($user['User']['id'], $this->currentUser['User']['id'], 'profile')) {
				$this->Session->setFlash(__('not_allowed_profile', true));
				$canView = false;
			}
		}
		if ($canView) {
			$friends = $this->User->GroupsUser->getFriends(0, 0, array('uid' => $user['User']['id']));
			$wallPosts = $this->User->WallPost->getWallPosts(10, 0, array('uid' => $user['User']['id']));
		} else {
			$friends = array();
			$wallPosts = array();
		}

		$isFriend = $this->User->GroupsUser->isFriend($this->currentUser['User']['id'], $user['User']['id']);
		//pass the profile data to the view
		$this->set(compact('friends', 'isFriend', 'user', 'wallPosts'));
	}

	function search(){

		//TODO: these results will need to be ranked by relationship, network, secondary relationships, and location, in that order.

		//get all the searchable profiles
		$results = $this->User->findAllBySearchable(true);

		$this->set('results', $results);
	}

	function signup() {
		$this->layout = 'simple_header';

		if (!empty($this->data)) {
			// make sure the passwords match, if not show the page again with all the current info except the password
			if ($this->data['User']['password'] != $this->Auth->password($this->data['User']['passwd'])) {
				$this->Session->setFlash(__('password_mismatch', true));
				unset($this->data['User']['password']);
				unset($this->data['User']['passwd']);
			// passwords match
			} else {
				// We need the hash here for the email
				$this->data['User']['hash'] =  sha1(date('Y-m-d') . Configure::read('Security.salt'));
				if ($this->User->signup($this->data)) {
					// send user email
					$this->Email->from		=	'Telame.com <admin@telame.com>';
					$this->Email->to		= $this->data['User']['slug'] . '<' . $this->data['User']['email'] . '>';
					$this->Email->subject	= 'Your ' . __('site_name', true) . ' account has been created.';
					$this->Email->sendAs	= 'both';
					$this->Email->template	= 'signup';
					$this->set('user', $this->data);
					$this->Email->send();

					// tell the user it's all good
					$this->Session->setFlash(__('user_saved', true));
				} else {
					$this->Session->setFlash(__('user_create_error'));
				}
				   $this->redirect('/');
				   exit;
			}
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