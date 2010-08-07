<?php
class UsersController extends AppController {

	var $components = array('Email');
	var $helpers = array('Text', 'Time');

	function beforeFilter(){
		parent::beforeFilter();

//		$this->Auth->allow('signup');

		if (strtolower($this->params['action']) != 'signup') {
			//add css and js that is common to all the actions in this controller
			$this->Includer->add('css', array(
				'base',
				'tall_header',
				'main_sidebar'
			));
			$this->Includer->add('script', array(
				'jquery',
				'base',
				'main_sidebar',
			));
		} else {
			$this->Includer->add('css', array(
				'base',
				'simple_header'
			));
			$this->Includer->add('script', array(
				'jquery',
				'base'
			));
			$this->layout = 'pages';
		}
	}

	//Before the render of all views in this controller
	function beforeRender() {
		//run the before render in the app controller
		parent::beforeRender();
		//set the css and script for the view
		$this->set('css_for_layout', $this->Includer->css());
		$this->set('script_for_layout', $this->Includer->script());
	}

	//A summary of whats new for the user.
	function index() {
		$wp = $this->User->WallPost->find('all');
	}

	function login(){
		$this->Includer->add('css', array(
			'users/login'
		));
	}

	/** delegate /users/logout request to Auth->logout method */
	function logout(){
		$this->redirect($this->Auth->logout());
	}

	function profile($slug){

		$this->Includer->add('script', array(
			'users/wall_input',
			'users/wall'
		));
		$this->Includer->add('css', array(
			'users/profile',
			'users/gallery',
			'users/summary',
			'users/actions',
			'users/wall',
			'users/wall_sidebar'
		));

		// get the user's info based on their slug
		$user = $this->User->getProfile($slug);

		if(!$user){
			$this->redirect(array('controller' => 'users', 'action' => 'profile', $this->currentUser['User']['slug']));
			exit;
		}

		$wallPosts = $this->User->WallPost->getWallPosts(10, 0, array('uid' => $user['User']['id']));

		//page title
		$this->set('title_for_layout', __('site_name', true) . ' | ' . $user['Profile']['full_name']);

		//pass the profile data to the view
		$this->set(compact('user', 'wallPosts'));
	}

	function search(){

		//TODO: these results will need to be ranked by relationship, network, secondary relationships, and location, in that order.

		//get all the searchable profiles
		$results = $this->User->findAllBySearchable(true);

		$this->set('results', $results);
	}

	function signup() {
		if (!empty($this->data)) {
			if ($this->data['User']['password'] != $this->Auth->password($this->data['User']['passwd'])) {
				$this->Session->setFlash(__('password_mismatch', true));
				unset($this->data['User']['password']);
				unset($this->data['User']['passwd']);
			} else {
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
				if (!$this->User->save($this->data)) {
					$this->Session->setFlash(__('user_create_error'));
					$this->redirect('/');
					exit;
				}
				$this->Session->setFlash(__('user_saved', true));

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
				$this->Email->from		=  'Telame.com <admin@telame.com>';
				$this->Email->to		= $this->data['User']['slug'] . '<' . $this->data['User']['email'] . '>';
				$this->Email->subject	= 'Your ' . __('site_name', true) . ' account has been created.';
				$this->Email->send('Welcome to Telame.  You need to finish your account by validating your email. ' . $this->data['User']['hash']);
			}
		} else {
			// Default page to show
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

}