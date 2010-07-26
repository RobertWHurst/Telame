<?php
class UsersController extends AppController {

	//Controller config
	var $name = 'Users';
	
	
	function beforeRender() {
		parent::beforeRender();
		$this->set('css_for_layout', 'default.css');
	}

    function login(){
    }

    /** delegate /users/logout request to Auth->logout method */
    function logout(){
        $this->redirect($this->Auth->logout());
    }
}