<?php
class GroupsUser extends AppModel {

	var $belongsTo = array(
		'User',
		'Friend' => array(
			'className' => 'User',
			'foreignKey' => 'friend_id',
		)
	);

	function getFriends($limit = 0, $offset = 0, $arguments = false){

		$defaults = array(
			'friendList' => false,
			'uid' => false,
			'gid' => false
		);

		$options = parseArguments($defaults, $arguments);

		$this->Behaviors->attach('Containable');

		if ($options['uid'])
			$conditions['user_id'] = $options['uid'];		

		if ($options['gid'])
			$conditions['group_id'] = $options['gid'];

		if($options['friendList'])
			$conditions['list_id'] = $options['friendList'];

		$friends = $this->find('all', array(
			'conditions' => $conditions,
			'limit' => $limit,
			'offset' => $offset,
			'contain' => 'Friend.Profile'
		));

		return $friends;
	}
	
	function isFriend($uid, $fid) {
		
		$friend = $this->find('first', array('conditions' => array('user_id' => $uid, 'friend_id' => $fid)));
		if (!$friend) {
			return false;
		} else {
			return true;
		}
	}
	
	function listGroups($uid, $fid) {
		$this->Behaviors->attach('Containable');
		$groups = $this->find('first', array(
			'conditions' => array(
				'user_id' => $uid,
				'friend_id' => $fid,
			),
			'contain' => array(
			)
		));
		return $groups;
	}
}