<?php
class User extends AppModel {
	var $name = 'User';

	// Connect to the ACL table
	var $actsAs = array('Acl' => array('type' => 'requester'));

	var $belongsTo = array(
		'Media' => array(
			'ClassName' => 'Media',
			'foreignKey' => 'avatar_id',
		),
	);

	var $hasAndBelongsToMany = array('Group');

	var $hasMany = array(
		'Album' => array(
			'dependent'=> true,
		),
		'GroupsUser',
		'Notification',
		'WallPost' => array(
			'order' => 	'WallPost.id DESC',
			'dependent' => true,
			'exclusive' => true,
		),
		'Message'
	);

	var $hasOne = array(
		'Profile' => array(
			'dependent' => true,
		)
	);
	
	var $validate = array(
		'email' => array(
			'uniqueEmail' => array(
				'rule' => 'isUnique',
				'message' => 'That email is already in use',
			),
			'validEmail' => array(
				'rule' => array('email', true), // attempt to validate the host
				'allowEmpty' => false,
				'required' => true,
				'message' => 'A valid email address is required',
				// on => create or update - will only be enforced then, default is null
			),
		), 
		'password' => array(
			'rule' => array('minLength', 8),
			'allowEmpty' => false,
			'required' => true,
			'message' => 'Password must be 8 characters long', // to internationalize it must be done in the view
		),
		'slug' => array(
			'rule' => 'isUnique',
			'allowEmpty' => false,
			'required' => true,
			'message' => 'That username is already in use',
		),
	);
	
// -------------------- Callback functions

	function beforeFind($queryData) {
		if (isset($queryData['conditions']['User.email'])) {
			$queryData['conditions']['User.email'] = strtolower($queryData['conditions']['User.email']);
		}
		return $queryData;
	}


// -------------------- ACL functions
	function parentNode() {
		if (!$this->id) {
			return null;
		}
		$data = $this->read();
		if (!$data['User']['group_id']){
			return null;
		} else {
			return array('model' => 'Group', 'foreign_key' => $data['User']['group_id']);
		}
	}

// --------------------- Custom functions

	function getIdFromSlug($slug) {
		$this->recursive = -1;
		$user = $this->find('first', array('conditions' => array('lower(slug)' => Sanitize::clean(strtolower($slug))), 'fields' => 'id'));
		return $user['User']['id'];
	}

	function getSlugFromId($uid) {
		$this->recursive = -1;
		$user = $this->find('first', array('conditions' => array('id' => Sanitize::clean(intval($uid))), 'fields' => 'slug'));
		return $user['User']['slug'];
	}

	function getProfile($slug){
		$conditions = array(
		);

		//get the profile
		$this->Behaviors->attach('Containable');

		return $this->find('first', array(
			'conditions' => array('lower(slug)' => Sanitize::clean(strtolower($slug))),
			'contain' => array(
				'Media',
				'Profile',
			)
		));
	}
	
	// takes a user id and makes them a random directory, returns the dir in an array, or false if it doesn't work
	function makeUserDir($id) {
		$baseDir = APP . 'users' . DS;
		$home = rand(0, 31500) . DS;
		$sub = rand(0, 31500) . DS;
		
		$userHome = $baseDir . $home . $sub . $id . DS;
		if (mkdir($userHome, 0777, true)) {
			return array('home' => $home, 'sub' => $sub);
		} else {
			return false;
		}
	}
}

