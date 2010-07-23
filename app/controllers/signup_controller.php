<?php
class SignupController extends AppController {

	//Controller config
	var $name = 'Signup';
	var $uses = null;
	
	function index(){
		
		//set up the layout
		$this->set('title_for_layout', 'Telame');
		$this->set('css_for_layout', 'style.css');
		$this->layout = 'external';
		$this->render('index');
		
	}
	
}