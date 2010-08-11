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
			'gid' => false,
		);

		$options = $this->parseArguments($defaults, $arguments);

		$this->Behaviors->attach('Containable');

		$conditions['user_id'] = $options['uid'];

		if ($options['gid']) {
			$conditions['group_id'] = $options['gid'];
		}

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
}