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
		//run the before render in the app controller
		parent::beforeRender();	
		//set the css and script for the view
		$this->set('css_for_layout', $this->Includer->css());
		$this->set('script_for_layout', $this->Includer->script());
	}

	//A summary of whats new for the user.
	function index() {
		$this->redirect('/' . $this->currentUser['User']['slug']);
		exit();
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
			$this->data = array_merge($this->data, $user);
			// I tried putting this in the beforeSave() callback, but couldn't get it to work, shrug.
			$i = 0;
			// Loop over all meta data and correct the structure
			foreach ($this->data['UserMeta'] as $id => $value) {
				$this->data['temp'][$i] = array(
					'id' => $id, 
					'meta_value' => $value,
					'user_id' => $this->data['User']['id']
				);
				$i++;
			}
			// overwrite the UserMeta with the correct info
			$this->data['UserMeta'] = $this->data['temp'];
			// unset the temp var we used
			unset($this->data['temp']);
			// saveAll will save the model and associaions
			$this->User->saveAll($this->data);
			// redirect back to the user's profile
			$this->redirect('/' . $user['User']['slug']);
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
				'Media',
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
		$this->set('title_for_layout', Configure::read('SiteName') . ' - ' . $user['UserMeta']['first_name']['value'] . ' ' . $user['UserMeta']['last_name']['value']);

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