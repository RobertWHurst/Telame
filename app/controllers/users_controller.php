<?php
class UsersController extends AppController {

	//Controller config
	var $name = 'Users';
	var $helpers = array('RenderProfile');
	
	function beforeFilter(){
		parent::beforeFilter();
		
		//add css and js that is common to all the actions in this controller
		$this->Includer->add('css', array('default', 'users/wall'));
		$this->Includer->add('script', array('jquery', 'users/profile'));
	}

	//Before the render of all views in this controller
	function beforeRender() {
		parent::beforeRender();	
		
		//set the css and script for the view
		$this->set('css_for_layout', $this->Includer->css());
		$this->set('script_for_layout', $this->Includer->script());
	}

	// this function fetches the user's avatar
	function avatar($uid = null) {
		if (empty($uid)) {
			$this->cakeError('error404');
		}
		// media view for files
		$this->view = 'Media';
		// we don't need all the associations
		$this->User->recursive = -1;
		$user = $this->User->findById($uid);
		$params = array(
			'id' => trim($user['User']['avatar']),
			'name' => $user['User']['slug'],
			'download' => false,
			'extension' => 'png',
			'path' => APP . 'users' . DS . $uid . DS . 'images' . DS . 'profile' . DS,
			'cache' => '5 days',
	   );
	   $this->set($params);
	}

	//A summary of whats new for the user.
	function index() {
		$this->redirect('/p');
		exit();
	}

	function edit($slug = false) {
		if ($slug && empty($this->data)) {
			// call the profile function get fill all of our info for us
			$this->profile($slug);
			$this->set('edit', true);
			$this->render('profile');
		}
		if (!empty($this->data)) {
			$this->User->save($this->data);
			$this->redirect('/p');
			exit();
		}
	}

	function login(){
	}

	/** delegate /users/logout request to Auth->logout method */
	function logout(){
		$this->redirect($this->Auth->logout());
	}

	function profile($slug = false) {

		//if no user slug is given then get the current user's profile slug and redirect them to it.
		if(!$slug){
			$this->redirect('/' . $this->currentUser['User']['slug']);
			exit();
		}

		//get the profile
		$this->User->recursive = 2;
		$this->User->Behaviors->attach('Containable');
		$user = $this->User->find('first', array(
			'conditions' => array(
				'lower(slug)' => strtolower($slug)
			),
			'contain' => array(
				'Friend' => array(
					'User'
				),
				'UserMeta',
				'WallPost' => array(
					'PostAuthor'
				)
			)
		));
		if (!$user) {
			$this->redirect('/');
		}
		//page title
		//$this->set('title_for_layout', "Telame - {$user['UserMeta']['first_name']} {$user['UserMeta']['last_name']}");

		//pass the profile data to the view
		$this->set(compact('user'));
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