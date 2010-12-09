<?php
class GroupsUser extends AppModel {

	public $belongsTo = array(
		'Friend' => array(
			'className' => 'User',
			'foreignKey' => 'friend_id',
		),
		'User',
	);

	public function getFriends($arguments = false){

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

	public function getBirthdays($uid) {
		$friendIds = $this->getFriendIds($uid);
		$this->User->Profile->recursive = -1;
		$users = $this->User->Profile->find('all', array(
			'conditions' => array(
				//'Profile.dob LIKE' => '%' . date('-m-d', strtotime('now')),
				'User.id' => $friendIds,
			),
			'contain' => array(
				'User',
			)
		));
		$count = count($users);
		for ($i=0; $i<$count; $i++) {
			$bday = date('-m-d', strtotime($users[$i]['Profile']['dob']));
			$today = date('-m-d');
			if ($bday != $today) {
				unset($users[$i]);
			}
		}
		return $users;
	}

	/*  returns all your friend ids in list form
	 *  if gid is set it will only return friends in that group
	 */ 
	public function getFriendIds($uid, $gid = false) {
		// get our list of 'friends'
		$this->recursive = -1;
		$fids = $this->find('list', array(
			'conditions' => array(
				'user_id' => $uid,
				($gid) ? array('group_id' => $gid) : '',
			), 
			'fields' => array(
				'friend_id'
			)
		));

		// get a list of where we are the friend
		$this->recursive = -1;
		$uids = $this->find('list', array(
			'conditions' => array(
				'friend_id' => $uid,
				'user_id' => $fids, // only look at people that we have put on our list
			),
			'fields' => array(
				'user_id',
			),
		));

		// compare the two arrays and only return what's common. otherwise there isn't a two way friendship
		return array_intersect($fids, $uids);
	}

	public function isFriend($uid, $fid) {
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
	public function listGroups($uid, $fid) {
		$this->recursive = -1;
		$groups = $this->find('first', array(
			'conditions' => array(
				'GroupsUser.user_id' => $uid,
				'GroupsUser.friend_id' => $fid,
			)
		));
		return $groups;
	}

	public function requestSent($uid, $fid) {
		if ($this->find('first', array('conditions' => array('user_id' => $uid, 'friend_id' => $fid)))) {
			return true;
		} else {
			return false;
		}
	}
}