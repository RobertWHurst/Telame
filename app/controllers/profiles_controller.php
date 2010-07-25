<?php
class ProfilesController extends AppController {

	//Controller config
	var $name = 'Profiles';
	var $uses = null; //TODO: '$uses=null' can be removed once the db is configured

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
    
	function profile() {
		//TODO: Replace fake user with the selected user from the database.
		
		//set up the layout
		$this->set('title_for_layout', 'Telame - Mr Bolts');
	}

    function login() {
    }

    /** delegate /users/logout request to Auth->logout method */
    function logout() {
        $this->redirect($this->Auth->logout());
    }

}