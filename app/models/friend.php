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
		)
	);

	function getFriends($limit = 0, $offset = 0, $uid){
				
		$this->Behaviors->attach('Containable');
				
		$friends = $this->find('all', array(
			'conditions' => array(
				'parent_user_id' => $uid,
			),
			'contain' => array(
				'UserFriend.Profile'
			),
			'limit' => $limit,
			'offset' => $offset
		));
		
		return $friends;
	}
	
	function getFriendList($uid) {
		return $this->find('list', array(
			'conditions' => array(
				'parent_user_id' => $uid,
			),
			'fields' => array(
				'child_user_id'
			)
		));
	}
}