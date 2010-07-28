<?php
class UsersController extends AppController {

	//Controller config
	var $name = 'Users';
	var $helpers = array('RenderProfile');

	//Before the render of all views in this controller
	function beforeRender() {
		parent::beforeRender();
		$this->set('css_for_layout', 'default.css');
	}

	//A summary of whats new for the user.
	function index() {
		//TODO: Replace fake user with the selected user from the database.

		//set up the layout
		$this->set('title_for_layout', 'Telame - Error');
	}

	function edit($id = false) {
		$this->data = Sanitize::clean($this->data);
		$this->Profile->save($this->data);
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
			$user = $this->Profile->User->find('first', array('conditions' => array('slug' => $slug)));
    		$profile = $this->Profile->findByUser_id();

    		$this->redirect("/profiles/profile/{$profile['Profile']['slug']}");
    		exit(); // always exit after a redirect
    	}

		//get the profile
		$profile = $this->Profile->findBySlug($slug);
		$this->Profile->User->WallPost->recursive = -1;
		$wallPosts = $this->Profile->User->WallPost->find('all', array('conditions' => array('user_id' => Configure::read('UID'))));

		//page title
		$this->set('title_for_layout', "Telame - {$profile['ProfileMeta']['first_name']} {$profile['ProfileMeta']['last_name']}");

		//pass the profile data to the view
		$this->set(compact('profile', 'wallPosts'));
	}

	function search(){

		//TODO: these results will need to be ranked by relationship, network, secondary relationships, and location, in that order.

		//get all the searchable profiles
		$results = $this->Profile->findAllBySearchable(true);

		$this->set('results', $results);
	}

	function jx_search(){

		//TODO: these results will need to be ranked by relationship, network, secondary relationships, and location, in that order.

		//get all the searchable profiles
		$results = $this->Profile->findAllBySearchable(true);

		foreach($results as $row){
			$users[] = array('name' => $row['ProfileMeta']['first_name']. ' ' .$profile['ProfileMeta']['last_name'], 'slug' => $profile['Profile']['slug']);
		}
		$this->set('results', $results);
	}

}