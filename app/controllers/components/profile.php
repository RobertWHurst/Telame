<?php
class ProfileComponent extends Object {

	var $components = array('Aacl');

	function initialize(&$controller, $settings = array()) {
		// saving the controller reference for later use
		$this->controller =& $controller;
	}


	function getProfile($slug) {
		$this->User = Classregistry::init('User');

		$canView = false;
		$canRequest = false;

		if (!$slug) {
			$user = $this->controller->currentUser;
		} else {
			// get the user's info based on their slug
			$user = $this->User->getProfile($slug);
		}

		if(!$user){
			$this->controller->redirect(array('controller' => 'users', 'action' => 'profile', $this->controller->currentUser['User']['slug']));
			exit;
		}

		// check if the requested user is yourself
		if ($this->controller->currentUser['User']['id'] != $user['User']['id']) {

			// Do permission check
			if($this->Aacl->checkPermissions($user['User']['id'], $this->controller->currentUser['User']['id'], 'profile')) {
				$canView = true;
			} else {
				$this->controller->Session->setFlash(__('not_allowed_profile', true), 'default', array('class' => 'warning'));
			}
			// are you friends with this person
			$isFriend = $this->User->GroupsUser->isFriend($this->controller->currentUser['User']['id'], $user['User']['id']);
			if (!$isFriend) {
				if (!$this->User->GroupsUser->requestSent($this->controller->currentUser['User']['id'], $user['User']['id'])) {
					$canRequest = true;
				}
			}
		} else {
			// These are defaults for viewing your own profile
			$canView = true;
			$isFriend = true;
		}

		if ($canView) {
			$friends = $this->User->GroupsUser->getFriends(array('uid' => $user['User']['id'], 'random' => true, 'limit' => 10));
			$wallPosts = $this->User->WallPost->getWallPosts(array('uid' => $user['User']['id'], 'ActionRecipient' => true));
		} else {
			$friends = array();
			$wallPosts = array();
		}

		//get gallery position data
		$galleryPosData = unserialize($user['Profile']['gallery_pos_data']);

		//pass the profile data to the view
		$this->controller->set(compact('canRequest', 'friends', 'user', 'wallPosts', 'galleryPosData'));
		return $user;
	}

}
