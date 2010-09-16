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
			'order' => array('Friend.first_name', 'Friend.last_name'),
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
			$options['order'] = 'RANDOM()';
		}

		$this->recursive = 2;
		$friends = $this->find('all', array(
			'conditions' => $conditions,
			'contain' => array(
				'Friend.Profile',
			),
			'limit' => $options['limit'],
			'offset' => $options['offset'],
			'order' => $options['order'],
		));

		return $friends;
	}

	/* returns all your friend ids in list form
	 * Friend_id and User_id seem backwards, but we want to do a test based on which users are friends with you, they have confirmed so
	 */
	function getFriendIds($uid, $gid = false) {
		$this->recursive = -1;
		$fids = $this->find('list', array(
			'conditions' => array(
				'friend_id' => $uid,
				($gid) ? array('group_id' => $gid) : '',
			), 
			'fields' => array(
				'user_id'
			)
		));
		return $fids;
	}

	function isFriend($uid, $fid) {
		// checking against self
		if ($uid == $fid) {
			return true;
		}
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

	function requestSent($uid, $fid) {
		if ($this->find('first', array('conditions' => array('user_id' => $uid, 'friend_id' => $fid)))) {
			return true;
		} else {
			return false;
		}
	}
}