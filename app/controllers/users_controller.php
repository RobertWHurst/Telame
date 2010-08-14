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

//		$this->Auth->allow('signup');
	}
	
	//A summary of whats new for the user.
	function index() {
		$wp = $this->User->WallPost->find('all');
	}

	function login(){
	}

	/** delegate /users/logout request to Auth->logout method */
	function logout(){
		$this->redirect($this->Auth->logout());
	}

	function profile($slug){
		//set the layout
		$this->layout = 'profile';
		
		// get the user's info based on their slug
		$user = $this->User->getProfile($slug);

		if(!$user){
			$this->redirect(array('controller' => 'users', 'action' => 'profile', $this->currentUser['User']['slug']));
			exit;
		}

		// Do permission check
		if ($this->currentUser['User']['id'] != $user['User']['id']) {
// FIXME: Remove the @ symbol
			if (!@$this->Acl->check(array('model' => 'User', 'foreign_key' => $this->currentUser['User']['id']), 'User::' . $user['User']['id'] . '/profile', 'read')) {
				$this->Session->setFlash(__('not_allowed_profile', true));
				$this->redirect('/');
				exit;
			}
		}

		$friends = $this->User->GroupsUser->getFriends(0, 0, array('uid' => $user['User']['id']));
		$wallPosts = $this->User->WallPost->getWallPosts(10, 0, array('uid' => $user['User']['id']));

		//pass the profile data to the view
		$this->set(compact('friends', 'user', 'wallPosts'));
	}

	function search(){

		//TODO: these results will need to be ranked by relationship, network, secondary relationships, and location, in that order.

		//get all the searchable profiles
		$results = $this->User->findAllBySearchable(true);

		$this->set('results', $results);
	}

	function signup() {
		$this->layout = 'pages';
		
		if (!empty($this->data)) {
			// make sure the passwords match, if not show the page again with all the current info except the password
			if ($this->data['User']['password'] != $this->Auth->password($this->data['User']['passwd'])) {
				$this->Session->setFlash(__('password_mismatch', true));
				unset($this->data['User']['password']);
				unset($this->data['User']['passwd']);
			// passwords match
			} else {
				// if they provided a slug use it, otherwise use their email
				$slug = (!empty($this->data['User']['slug']) ? $this->data['User']['slug'] : $this->data['User']['email']);
	
				// create a new user
				$this->User->create();
				// fill array with data we need in the db
				$this->data['User']['added'] = date('Y-m-d');
				$this->data['User']['accessed'] = date('Y-m-d');
				$this->data['User']['level'] = '1';
				$this->data['User']['invisible'] = false;
				$this->data['User']['slug'] = $slug;
				$this->data['User']['type'] = '1';
				$this->data['User']['searchable'] = true;
				$this->data['User']['avatar_id'] = '-1';
				$this->data['User']['active'] = false;
				$this->data['User']['hash'] =  sha1(date('Y-m-d') . Configure::read('Security.salt'));
	
				// save the user
				if (!$this->User->save(Sanitize::clean($this->data))) {
				    $this->Session->setFlash(__('user_create_error'));
				    $this->redirect('/');
				    exit;
				}
	
				// make their home directory structure
				$dir = $this->User->makeUserDir($this->User->id);
				if ($dir != false) {
				    $this->User->saveField('home_dir', $dir['home']);
				    $this->User->saveField('sub_dir', $dir['sub']);
				} else {
				    $this->Session->setFlash(__('user_create_error'));
				    $this->redirect('/');
				    exit;
				}
				// send user email
				$this->Email->from		=  'Telame.com <admin@telame.com>';
				$this->Email->to		= $this->data['User']['slug'] . '<' . $this->data['User']['email'] . '>';
				$this->Email->subject	= 'Your ' . __('site_name', true) . ' account has been created.';
				$this->Email->send('Welcome to Telame.  You need to finish your account by validating your email. ' . $this->data['User']['hash']);
				// tell the user it's all good
				$this->Session->setFlash(__('user_saved', true));
			}
		}
	}

	function jx_search(){

		//TODO: these results will need to be ranked by relationship, network, secondary relationships, and location, in that order.

		//get all the searchable profiles
		$results = $this->User->findAllBySearchable(true);

		foreach($results as $row){
			$users[] = array('name' => $row['UserMeta']['first_name']. ' ' .$profile['UserMeta']['last_name'], 'slug' => $profile['User']['slug']);
		}
		$this->set('results', $results);
	}

	function _forceSSL() {
		$this->redirect('https://' . env('SERVER_NAME') . $this->here);
	}

	function _unforceSSL() {
		$this->redirect('http://' . env('SERVER_NAME') . $this->here);
	}

}