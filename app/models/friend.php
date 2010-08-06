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
			'uid' => false,
			'list' => false
		);
		
		$options = $this->parseArguments($defaults, $arguments);
				
		//set the find mode
		if($options['list'])
			$mode = 'list';
		else{
			$this->Behaviors->attach('Containable');
			$mode = 'all';
			$findConfig['contain'] = array('UserFriend.Profile');
		}
		
		$findConfig = array(
			'conditions' => array(
				'parent_user_id' => $options['uid'],
			),
			'limit' => $limit,
			'offset' => $offset
		);
		
		$friends = $this->find($mode, $findConfig);
		
		return $friends;
	}
}