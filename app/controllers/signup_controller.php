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

}