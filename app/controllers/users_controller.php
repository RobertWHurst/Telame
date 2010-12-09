<?php
class UsersController extends AppController {

	public $components = array('Email', 'Profile', 'WallPosts');
	public $helpers = array('LinkRender', 'Markdown', 'Paginator', 'Time');

	public function beforeFilter(){
		parent::beforeFilter();

		$this->Security->blackHoleCallback = '_forceSSL';
		$this->Security->requireSecure('login', 'passwordReset', 'signup');
		if (!in_array($this->action, $this->Security->requireSecure) && env('HTTPS')) {
		 	$this->_unforceSSL();
		}
		$this->Auth->allow(array('confirm', 'passwordReset', 'signup'));

	}

	// used to fix aco tree function
	// umcomment this, plus the router, and the aco.php model file
	public function fix() {
//		$this->loadModel('Aco');
//		$this->Aco->recover();
//		die('here');
	}

	public function confirm($email = null, $hash = null) {
		$this->layout = 'tall_header';
		if (!is_null($email) && !is_null($hash) || !empty($this->data)) {
			if (!empty($this->data)) {
				$email = $this->data['User']['email'];
				$hash = $this->data['User']['hash'];
			}
			if ($this->User->confirm($email, $hash)) {
				$this->Session->setFlash(__('email_confirmed', true), 'default', array('class' => 'info new_user'));
				$this->redirect('/login');
			} else {
				$this->Session->setFlash(__('email_or_hash_failed', true), 'default', array('class' => 'error'));
				$this->redirect($this->referer());
			}
			exit;
		} else {
			$this->data['User']['email'] = $email;
			$this->data['User']['hash'] = $hash;
		}
	}

	public function passwordReset($email = null, $pass = null) {
		$this->layout = 'tall_header';
		if (!empty($this->data)) {
			// 4th step.  save their new password
			if (isset($this->data['User']['email']) && isset($this->data['User']['user_password'])) {
				$user = $this->User->find('first', array('conditions' => array('User.email' => $this->data['User']['email'], 'User.temp_password' => $this->data['User']['temp_password'])));
				if (!$user) {
					$this->Session->setFlash(__('email_password_incorrect', true));
					$this->redirect($this->referer());
					exit;
				}
				if (!$this->User->validateSingle('user_password', $this->data['User']['user_password']) || $this->data['User']['user_password'] != $this->data['User']['user_password_again']) {
					$this->Session->setFlash(__('validation_error', true), 'default', array('class' => 'error'));
					$this->set(compact('email', 'pass'));
					$this->render('/users/password_reset/new_password');
				} else {
					$this->User->id = $user['User']['id'];
					$this->User->saveField('password', $this->Auth->password($this->data['User']['user_password']));
					$this->User->saveField('temp_password', null);
					$this->redirect('/login');
					exit;
				}
			} else { // 2nd step.  we have their email, generate a new pass, store it, and send them an email
				$this->User->recursive = -1;
				$user = $this->User->find('first', array('conditions' => array('User.email' => $this->data['User']['email'])));
				if ($user) {
					$newPass = sha1(Configure::read('Security.salt') . $this->data['User']['email'] . microtime());
					$this->User->id = $user['User']['id'];
					$this->User->saveField('temp_password', $newPass);
					$emailSettings = Configure::read('EmailInfo');

					$this->Email->from		= $emailSettings['from'];
					$this->Email->to		= '<' . $this->data['User']['email'] . '>';
					$this->Email->subject	= __('site_name', true) . ' password reset.';
					$this->Email->sendAs	= 'both';
					$this->Email->template	= 'password_reset';
					$this->set('user', $user);
					$this->set('temp_password', $newPass);
					$this->Email->send();
				} else {
					$this->Session->setFlash(__('invalid_email', true));
				}
				$this->redirect('/');
				exit;
			}
		// should be 3rd step.  they clicked the link with email and new pass.  time to get a new password
		} else if (!is_null($email) && !is_null($pass)) {
			$user = $this->User->find('first', array('conditions' => array('User.email' => $email, 'User.temp_password' => $pass)));
			if (!$user) { // something is wrong, try again
				$this->Session->setFlash(__('email_password_incorrect', true));
				$this->redirect('/password_reset/' . $email);
				exit;
			} else {
				$this->set(compact('email', 'pass'));
				$this->render('/users/password_reset/new_password');
			}
		// email or temp pass wrong, get again
		} else if (!is_null($email) || !is_null($pass)) {
			$this->render('/users/password_reset/confirm');
		} else { // 1st step, get user's email to send new pass to
			$this->render('/users/password_reset/email');
		}
	}

	public function profile($slug) {
		// set the layout
		$this->layout = 'new_profile';
		$user = $this->Profile->getProfile($slug);

		if ($user) {
			$friends = $this->User->GroupsUser->getFriends(array('uid' => $user['User']['id'], 'random' => true, 'limit' => 12 ));
			$groups = $this->User->Group->getFriendLists(array('uid' => $this->currentUser['User']['id'], 'type' => 'list'));

			$this->WallPosts->getWallPosts(array(
				'uid' => $user['User']['id'],
			));

		} else {
			$friends = array();
			$wallPosts = array();
		}

		$this->set(compact('friends', 'groups'));
	}

	public function search(){
		$this->layout = 'tall_header_w_sidebar';

		// this is for a technique called Post/Redirect/Get
		if (!empty($this->data)) {
			// Set up the URL that we will redirect to
			$url = array('query' => $this->data['Search']['query'], 'controller' => 'users', 'action' => 'search');

			// If we have parameters, loop through and URL encode them
			if( is_array($this->data['Search']) ) {
				foreach($this->data['Search'] as &$search) {
					$search = urlencode($search);
				}
			}

			// Merge our URL-encoded data with the URL parameters set above...
			$params = array_merge($url, $this->data['Search']);

			// Do the (magical) redirect
			$this->redirect($params);
		}

		if(!isset($this->params['query'])){
			$this->redirect($this->referer());
		}

		// I tried moving this to the user model, but it didn't work.  maybe later to reduce code duplication
		$search = Sanitize::clean($this->params['query']);
		$this->User->recursive = -1;
		$this->paginate = array(
			'conditions' => array(
				'searchable' => true,
				'OR' => array(
					'User.first_name ILIKE' => '%' . $search . '%',
					'User.last_name ILIKE' => '%' . $search . '%',
					'User.slug ILIKE' => '%' . $search . '%',
					'User.email ILIKE' => '%' . $search . '%',
				)
			),
			'contain' => array(
				'Profile' => array(
					'Country'
				)
			),
			'limit' => Configure::read('PageLimit'),
			'order' => array(
				'User.first_name',
				'User.last_name',
			)
		);
		$results = $this->paginate('User');

		//make some changes to the data
		foreach($results as $key => $user) {
			//set defaults
			$results[$key]['Friend'] = array(
				'is_friend' => false,
				'list' => false,
				'request_sent' => false
			);

			//caculate if a user is a friend of the current user
			$results[$key]['Friend']['is_friend'] = $this->User->GroupsUser->isFriend($this->currentUser['User']['id'], $user['User']['id']);

			//if the user has been added
			if($results[$key]['Friend']['is_friend']) {
				//findout what list the user is part of
				$groups = $this->User->GroupsUser->listGroups($this->currentUser['User']['id'], $user['User']['id']);
				$results[$key]['Friend']['list'] = $this->User->Group->getGroupTitleById($groups['GroupsUser']['group_id']);
			} else {
				//findout if a friend request has been made
				$results[$key]['Friend']['request_sent'] = $this->User->GroupsUser->requestSent($this->currentUser['User']['id'], $user['User']['id']);
			}
		}

		//get all the searchable profiles
		$this->set(array('results' => $results, 'search_query' => $this->params['query']));
	}

	public function signup($email = null, $key = null) {
		$this->layout = 'simple_header';
		// data has been posted
		if (!empty($this->data)) {
			if (!$this->data['User']['agree']) {
				$this->Session->setFlash(__('tos_must_agree', true), 'default', array('class' => 'error'));
				$this->redirect($this->referer());
				exit;
			}
			// import the beta keys model
			$this->loadModel('BetaKey');

			// prepare to validate the user info
			$this->User->set($this->data);

			// check if all the fields validate
			if (!$this->User->validates()) {
				$this->Session->setFlash(__('validation_error', true), 'default', array('class' => 'error'));
			// all checks out
			} else {
				// We need the hash here for the confirmation email
				$this->data['User']['hash'] =  sha1(date('Y-m-d H:i:s') . $this->data['User']['email'] . Configure::read('Security.salt'));
				$this->data['User']['password'] = $this->Auth->password($this->data['User']['user_password']);

				$uid = $this->User->signup($this->data);
				if ($uid) {
					$this->Aacl->createAcl($uid);

					$emailSettings = Configure::read('EmailInfo');
					// send user email
					$this->Email->from		= $emailSettings['from'];
					$this->Email->to		= $this->data['User']['slug'] . '<' . $this->data['User']['email'] . '>';
					$this->Email->subject	= 'Your ' . __('site_name', true) . ' account has been created.';
					$this->Email->sendAs	= 'both';
					$this->Email->template	= 'signup';
					$this->set('user', $this->data);
					$this->Email->send();

					// Delete the beta key here, that way it's the last thing to be done, if something else fails, they can try again
					$key = $this->BetaKey->findByKey($this->data['User']['beta_key']);
					$this->BetaKey->delete($key['BetaKey']['id']);

					// tell the user it's all good
					$this->Session->setFlash(__('account_created', true), 'default', array('class' => 'info'));
				} else {
					$this->Session->setFlash(__('user_create_error'), 'default', array('class' => 'error'));
				}

				$this->redirect('/');
				exit;
			}
		} else { // end if (!empty$this->data)
			// add the beta key to the default $this->data array
			$this->data['User']['beta_key'] = $key;
			$this->data['User']['email'] = $email;
		}
		unset($this->data['User']['password']);
		unset($this->data['User']['passwd']);
	}


//----------------- Important functions we don't need to see often ------------------//

	public function login(){
		$this->layout = 'tall_header';
		$this->AuthExtension->checkRememberMe();
	}

	/** delegate /users/logout request to Auth->logout method */
	public function logout(){
		$this->AuthExtension->logout();
		$this->Auth->logout();

		$this->redirect('/');
	}

}