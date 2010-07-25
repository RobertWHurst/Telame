<?php
class AppController extends Controller {
	
	//add user athentication
    var $components = array('Auth');
    
	function beforeFilter() {
    
    	//force athenication against profiles
    	$this->Auth->userModel = 'Profile';
    	$this->Auth->loginAction = array('admin' => false, 'controller' => 'profiles', 'action' => 'login');
    	$this->Auth->fields = array('username' => 'email', 'password' => 'password');
	
	}
	
	function beforeRender() {
	
	}
	
}