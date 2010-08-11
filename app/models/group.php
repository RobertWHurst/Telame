<?php
class Group extends AppModel {

	var $hasAndBelongsToMany = array('User');

	function getFriends($uid) {
		
//		return $this->User->find('all', array('conditions' => array('User.id' => $uid)));
	
	}

}