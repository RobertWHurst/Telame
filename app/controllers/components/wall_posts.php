<?php
class WallPostsComponent extends Object {

	function initialize(&$controller, $settings = array()) {
		// saving the controller reference for later use
		$this->controller =& $controller;
	}

	function getWallPosts($args = false) {
		$this->controller->loadModel('WallPost');
	
		$defaults = array(
			'all_friends' => false,
			'rss_hash' => false,
			'rss_uid' => false,
			'selectedFriendList' => false,
			'uid' => false,
		);

		$options = parseArguments($defaults, $args);

		// if checkRss returns false, get current user id, otherwise set uid = return of checkrss
		if ($user_id = !$this->checkRss($options['rss_uid'], $options['rss_hash'])) {
			$user_id = $this->controller->currentUser['User']['id'];
		}

		if ($options['all_friends']) {
			$friends = $this->getFriends($user_id, $options['selectedFriendList']);
		}

		// get all the posts of all your friends
		$wallPosts = $this->controller->WallPost->getWallPosts($this->controller->currentUser['User']['id'], array(
			'all_friends' => $options['all_friends'] ? $friends : false,
			'uid' => $options['uid'] ? $options['uid'] : false,
		));

		$this->controller->set(compact('wallPosts'));
	}

	// takes a user id and their hash, and checks if it's valid.
	// returns false or uid
	private function checkRss($user_id, $hash) {
		if (is_null($user_id) || is_null($hash)) {
			return false;
		}
		if (!$this->controller->WallPost) {
			$this->controller->loadModel('WallPost');
		}
		if ( $this->controller->RequestHandler->isRss() ) {
			Configure::write('debug', 0);
			// this just checks that the hash is valid for the specified user
			$this->controller->WallPost->User->recursive = -1;
			$user = $this->controller->WallPost->User->find('first', array(
				'conditions' => array(
					'User.id' => $user_id,
					'User.rss_hash' => Sanitize::paranoid($hash)
				)
			));
			$this->controller->RequestHandler->setContent('rss');
			if (!$user) {
				return false;
			} else {
				return $user['User']['id'];
			}
		}
	}
	
	private function getFriends($user_id, $selectedFriendList) {
		// only load these if they are not already
		if (!isset($this->controller->Group)) {
			$this->controller->loadModel('Group');
		}
		if (!isset($this->controller->GroupsUser)) {
			$this->controller->loadModel('GroupsUser');
		}
		$friendLists = $this->controller->Group->getFriendLists(array('uid' => $user_id));

		$psudeoLists = array(
			'all' => array('Group' => array('title' => 'Everyone', 'id' => 0))
		);

		$friendLists = array_merge($psudeoLists, $friendLists);

		$friends = $this->controller->GroupsUser->getFriendIds($user_id, $selectedFriendList);
		array_push($friends, $user_id);

		$this->controller->set(compact('friendLists', 'selectedFriendList'));

		return $friends;
	}
}
