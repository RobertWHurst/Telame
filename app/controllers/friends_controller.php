<?php
class FriendsController extends AppController {
	
	//Before the render of all views in this controller
	function listFriends() {
		//get the current user id
		$uid = $this->currentUser['User']['id'];
		
		$temp = $this->Friend->getFriends(0, 0, array('uid' => $uid));
	}

}