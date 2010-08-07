<?php
class ProfilesController extends AppController {
	
	function beforeFilter() {
		parent::beforeFilter();
		//add css and js that is common to all the actions in this controller
		$this->Includer->add('css', array(
			'base',
			'tall_header',
			'main_sidebar'
		));
		$this->Includer->add('script', array(
			'jquery',
			'base',
			'main_sidebar',
		));
	}

	//Before the render of all views in this controller
	function beforeRender() {
		//run the before render in the app controller
		parent::beforeRender();
		//set the css and script for the view
		$this->set('css_for_layout', $this->Includer->css());
		$this->set('script_for_layout', $this->Includer->script());
	}

	function edit($slug = false) {
		// If the user is not an admin, and they're trying to edit somebody else's profile, redirect them to their own
		if (/*!$admin ||*/ strtolower($slug) != strtolower($this->currentUser['User']['slug'])) {
			$this->redirect('/e/' . $this->currentUser['User']['slug']);
			exit;
		}
		// the data array isn't empty, so let's save it
		// this must be first after basic validation
		if(!empty($this->data)) {
			//If the form data can be validated and saved...
			if($this->Profile->save($this->data)) {
				//Set a session flash message and redirect.
				$this->Session->setFlash("Profile Saved!");
				$this->redirect('/' . $this->currentUser['User']['slug']);
				exit;
			}
		}

		// there is a slug and there isn't any data, so edit functionality
		if ($slug && empty($this->data)) {
			$uid = $this->Profile->User->getIdFromSlug($slug);
			$this->data = $this->Profile->find('first', array('conditions' => array('user_id' => $uid)));
		}

 	}

}