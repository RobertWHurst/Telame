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
		
		$options = $this->parse_arguments($defaults, $arguments);
		
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
}