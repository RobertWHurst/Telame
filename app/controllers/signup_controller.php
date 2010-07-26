<?php
class SignupController extends AppController {

	//Controller config
	var $name = 'Signup';
	var $uses = null;

	function beforeFilter() {
		parent::beforeFilter();
		
		//allow this entire controller to be accessed without needing to login
		$this->Auth->allow('*');
	}

	function beforeRender() {
		parent::beforeRender();
		
		//set the css and layout
		$this->set('css_for_layout', 'pages.css');
		$this->layout = 'pages';
	}

	function index() {
		//set up the layout
		$this->set('title_for_layout', 'Signup');
	}

}