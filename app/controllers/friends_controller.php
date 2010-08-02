<?php
class FriendsController extends AppController {

	//Controller config
	var $name = 'Friends';
	
	//Before the render of all views in this controller
	function listFriends($uid) {
		$this->set('friends', $this->Friend->getFriendList($uid));
	}

}