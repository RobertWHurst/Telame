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
			$order = array('Friend.first_name', 'Friend.last_name');
		}

		$this->recursive = 2;
		$friends = $this->find('all', array(
			'conditions' => $conditions,
			'contain' => array(
				'Friend.Profile',
			),
			'limit' => $options['limit'],
			'offset' => $options['offset'],
			'order' => $order,
		));

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