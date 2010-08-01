<?php
class UsersController extends AppController {

	//Controller config
	var $name = 'Users';
	var $components = array('Cookie');
	var $helpers = array('Time');
	
	function beforeFilter(){
		parent::beforeFilter();
		//add css and js that is common to all the actions in this controller
		$this->Includer->add('css', array(
			'base',
			'tall_header',
			'users/main_sidebar'
		));
		$this->Includer->add('script', array(
			'jquery',
			'users/main_sidebar',
		));
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

	function edit($slug = false) {
		// If the user is not an admin, and they're trying to edit somebody else's profile, redirect them to their own
		if (/*!$admin ||*/ $slug != $this->currentUser['User']['slug']) {
			$this->redirect('/e/' . $this->currentUser['User']['slug']);
		}
		$this->User->recursive = -1;
		$user = $this->User->findBySlug($slug);
		// there is a slug and there isn't any data, so edit functionality
		if ($slug && empty($this->data)) {
			// call the profile function get fill all of our info for us
			$this->profile($slug);
			$this->set('edit', true);
			$this->render('profile');
		}
		// the data array isn't empty, so let's save it
		if (!empty($this->data)) {
			$this->User->id = $user['User']['id'];

			foreach ($this->data['UserMeta'] as $key => $val) {
				$this->User->setMeta($key, $val);
			} 

			// redirect back to the user's profile
			$this->redirect('/' . $user['User']['slug']);
			exit();
		}
	}

	function login(){	
		$this->Includer->add('css', array(
			'users/login'
		));
		
		//check if the user is logged in
		if ($this->Auth->user()) {
			//check to see if the user is to be remembered
			if (!empty($this->data) && $this->data['User']['auto_login']) {
				$cookie = array();
				$cookie['email'] = $this->data['User']['email'];
				$cookie['password'] = $this->data['User']['password'];
				$this->Cookie->write('Auth.User', $cookie, true, '+2 weeks');
				unset($this->data['User']['auto_login']);
			}
			
			//run the login
			$this->redirect($this->Auth->redirect());
		}
		
		//check if the post data was sent
		if (empty($this->data)) {
			
			//if not see if a cookie is in place to login with
			$cookie = $this->Cookie->read('Auth.User');
			
			//check to see if the cookie has data
			if (!is_null($cookie)) {
				if ($this->Auth->login($cookie)) {
					//  Clear auth message, just in case we use it.
					$this->Session->del('Message.auth');
					$this->redirect($this->Auth->redirect());
				} else { // Delete invalid Cookie
					$this->Cookie->del('Auth.User');
				}
			}
		}

	}

	/** delegate /users/logout request to Auth->logout method */
	function logout(){
		$this->redirect($this->Auth->logout());
	}

	function profile($slug = false) {

		$this->Includer->add('script', array(
			'users/wall_input',
			'users/wall'
		));
		$this->Includer->add('css', array(
			'users/profile',
			'users/profile_sidebar',
			'users/gallery',
			'users/summary',
			'users/actions',
			'users/wall'
		));

		//if no user slug is given then get the current user's profile slug and redirect them to it.
		if(!$slug){
			$this->redirect('/' . $this->currentUser['User']['slug']);
			exit();
		}

		// get the user's info based on their slug
		$user = $this->User->getProfile($slug);
		
		if (!$user) {
			$this->redirect('/');
		}
		
		//get the metadata for various parts of the profile page (privacy options too)
		$this->User->id = $user['User']['id'];
		$userMeta = $this->User->getMeta();
		
		//data displayed on the summary
		$name = ucwords($userMeta['first_name'] . ' ' . $userMeta['last_name']);
		$summary = array(
			//'location' => array('label' => 'Location:', 'value' => $userMeta['location']),
			'location' => array('label' => 'Location:', 'value' => '[location]'),
			'sex' => array('label' => 'Sex:', 'value' => '[sex]'),
			'born' => array('label' => 'Born:', 'value' => '[born]')
		);
		
		//gallery data
		$gallery = array(
			'gallery_mode' => $userMeta['gallery_mode']
		);
		
		//page title
		$this->set('title_for_layout', Configure::read('SiteName') . ' - ' . ucwords($userMeta['first_name']) . ' ' . ucwords($userMeta['last_name']));

		//pass the profile data to the view
		$this->set(compact('user', 'summary', 'gallery', 'name'));
	}

	function search(){

		//TODO: these results will need to be ranked by relationship, network, secondary relationships, and location, in that order.

		//get all the searchable profiles
		$results = $this->User->findAllBySearchable(true);

		$this->set('results', $results);
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