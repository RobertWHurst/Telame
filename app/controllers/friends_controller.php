<?php
class FriendsController extends AppController {

	var $uses = array();
	
	function friendList($slug = false) {
		$this->loadModel('User');
		//set the layout
		$this->layout = 'profile';

		if (!$slug) {
			$user = $this->currentUser;
		} else {
			$user = $this->User->getProfile($slug);
		}


		$friends = $this->User->GroupsUser->getFriends(0, 0, array('uid' => $user['User']['id']));
		
		$this->set(compact('friends', 'user'));
	}

}

?>