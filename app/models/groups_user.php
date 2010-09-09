<?php
class GroupsUser extends AppModel {

	var $belongsTo = array(
		'User',
		'Friend' => array(
			'className' => 'User',
			'foreignKey' => 'friend_id',
		)
	);

	function getFriends($arguments = false){

		$defaults = array(
			'friendList' => false,
			'gid' => false,
			'limit' => 10,
			'offset' => 0,
			'random' => false,
			'uid' => false,
		);

		$options = parseArguments($defaults, $arguments);

		$this->Behaviors->attach('Containable');

		if ($options['uid']) {
			$conditions['user_id'] = $options['uid'];
		}

		if ($options['gid']) {
			$conditions['group_id'] = $options['gid'];
		}

		if ($options['friendList']) {
			$conditions['list_id'] = $options['friendList'];
		}

		if ($options['random']) {
			$order = 'RANDOM()';
		} else {
			// this will be overridden below, so it's useless
			$order = '';
		}

		$this->recursive = 2;
		$friends = $this->find('all', array(
			'conditions' => $conditions,
			'contain' => 'Friend.Profile',
			'limit' => $options['limit'],
			'offset' => $options['offset'],
			'order' => $order,
		));

		// not random, order by full name
		if (!$options['random']) {
			$friends = Set::sort($friends, '{n}.Friend.Profile.full_name', 'asc');
		}
		return $friends;
	}

	function isFriend($uid, $fid) {
		// make sure they are on our list
		$friend = $this->find('first', array('conditions' => array('user_id' => $uid, 'friend_id' => $fid)));
		// they are on our list, are we on theirs?
		if ($friend) {
			// make sure we are on their list
			$friend = $this->find('first', array('conditions' => array('user_id' => $fid, 'friend_id' => $uid)));
			if ($friend) {
				return true;
			}
		}
		return false;
	}

	// takes User_ID and Friend_ID and returns what group the friend is in
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