<?php
class ProfilesController extends AppController {
	
	function beforeFilter() {
		parent::beforeFilter();
		$this->Auth->allow('add');
	}

	function add($email, $hash) {
		// basic check for valid hash
		if (!$hash || strlen($hash) != 40 || !$email) {
			$this->redirect('/');
			exit;
		}
		if (!empty($this->data)) {
			$this->Profile->create();
			$this->Profile->save($this->data);
			$this->redirect('/');
			exit;
		} else {
			$user = $this->Profile->User->find('first', array('conditions' => array('lower(email)' => strtolower($email))));
			// check the user hash matches
			if ($hash == $user['User']['hash']) {
				// it matches, let's setup a basic profile
				$this->Profile->User->id = $user['User']['id'];
				$this->Profile->User->saveField('active', true);

				// set vars and show profile
				$this->set(compact('email', 'hash'));
				
			} else {
				$this->Session->setFlash(__('user_hash_error', true), 'default', array('class' => 'error'));
				$this->redirect('/');
				exit;
			}
		}
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
				$this->Session->setFlash(__('profile_updated', true), 'default', array('class' => 'info'));
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