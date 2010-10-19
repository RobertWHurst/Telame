<?php
class BreweryProjectsController extends BreweryAppController {

	function beforeFilter() {
		parent::beforeFilter();
	}
	
	function beforeRender() {
		parent::beforeRender();
		
		$this->layout = 'tall_header';
	}

	function index() {
		$comments = $this->BreweryProject->BreweryComment->find('all');
		$this->set(compact('comments'));
	}
}