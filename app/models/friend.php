<?php
class Friend extends AppModel{
	var $name = 'Friend';

	var $belongsTo = array(
		'User' => array(
			'className' => 'User',
			'foreignKey' => 'parent_user_id'
		),
		'UserFriend' => array(
			'className' => 'User',
			'foreignKey' => 'child_user_id'
		),
		'FriendList' => array(
			'className' => 'FriendList',
			'foreignKey' => 'list_id'		
		)
	);

	function getFriends($limit = 0, $offset = 0, $arguments = false){
					
		$defaults = array(
			'friendList' => false,
			'uid' => false
		);
		
		$options = $this->parseArguments($defaults, $arguments);
		
		$this->Behaviors->attach('Containable');	
			
		$conditions['parent_user_id'] = $options['uid'];
		
		if($options['friendList'])
			$conditions['list_id'] = $options['friendList'];
		
		$friends = $this->find('all', array(
			'conditions' => $conditions,
			'limit' => $limit,
			'offset' => $offset,
			'contain' => 'UserFriend.Profile'
		));
		
		return $friends;
	}
	
	// takes two user id's and finds out if they're friends.
	// the first user is the parent and the second is the friend
	// returns true or false
	function isFriend($uid, $fid) {
		$friend = $this->find('first', array('conditions' => array('parent_user_id' => $uid, 'child_user_id' => $fid)));
		// if we have a row, then there is at least have a connection
		if ($friend) {
			return true;
		} else {
			return false;
		}
	}
}