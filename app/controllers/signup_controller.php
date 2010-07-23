<?php
class SignupController extends AppController {

	//Controller config
	var $name = 'Signup';
	var $uses = null;

	function beforeRender() {
		parent::beforeRender();
		$this->set('css_for_layout', 'external.css');
		$this->layout = 'external';
	}

	function index() {
		//set up the layout
		$this->set('title_for_layout', 'Telame');
	}

	function features() {
		//set up the layout
		$this->set('title_for_layout', 'Telame - Features');
	}

	// This won't be here once the login component is used.
	function login() {
		//set up the layout
		$this->set('title_for_layout', 'Telame - Login');
		$this->render('index');
	}

}