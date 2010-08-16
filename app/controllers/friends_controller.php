<?php
class FriendsController extends AppController {

	var $uses = array();
	
	function friendList($slug = false) {
		$this->loadModel('User');
		//set the layout
		$this->layout = 'profile';

		if (!$slug) {
			$slug = $this->currentUser['User']['slug'];
			$user = $this->currentUser;
		}
		
		$uid = $this->User->getIdFromSlug($slug);

		$friends = $this->User->GroupsUser->getFriends(0, 0, array('uid' => $uid));
		
		$this->set(compact('friends', 'user'));
	}

}

?>