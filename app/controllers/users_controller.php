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
		$this->User->save($this->data);
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
			$user = $this->User->find('first', array('conditions' => array('id' => Configure::read('UID'))));

    		$this->redirect("/p/{$user['User']['slug']}");
    		exit(); // always exit after a redirect
    	}

		//get the profile
		$this->User->recursive = 2;
		$this->User->Behaviors->attach('Containable');
		$user = $this->User->find('first', array(
								'conditions' => array(
									'lower(slug)' => strtolower($slug)
								), 
								'contain' => array(
									'ProfileMeta', 
									'WallPost' => array(
										'Poster'
									)
								)
							)
						);
		
		//page title
		$this->set('title_for_layout', "Telame - {$user['ProfileMeta']['first_name']} {$user['ProfileMeta']['last_name']}");

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
			$users[] = array('name' => $row['ProfileMeta']['first_name']. ' ' .$profile['ProfileMeta']['last_name'], 'slug' => $profile['Profile']['slug']);
		}
		$this->set('results', $results);
	}

}