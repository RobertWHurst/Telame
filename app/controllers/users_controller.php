<?php
class UsersController extends AppController {

	//Controller config
	var $name = 'Users';
	var $components = array('Cookie');
	var $helpers = array('Text', 'Time');

	function beforeFilter(){
		parent::beforeFilter();
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
		$this->set('title_for_layout', Configure::read('SiteName') . ' | ' . $user['Profile']['full_name']);

		//pass the profile data to the view
		$this->set(compact('user', 'wallPosts'));
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