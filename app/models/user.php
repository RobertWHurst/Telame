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

	var $hasMany = array(
		'Album',
		'Friend' => array(
			'ClassName' => 'User',
			'foreignKey' => 'child_user_id'
		),
		'Notification',
		'WallPost' => array(
			'order' => 	'WallPost.id DESC',
			'dependent' => true,
			'exclusive' => true
		)
	);

	var $hasOne = array(
		'Profile'
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
			'message' => 'That slug is already in use',
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
		return null;
	}

// --------------------- Custom functions

	function getIdFromSlug($slug) {
		$this->recursive = -1;
		$user = $this->find('first', array('conditions' => array('lower(slug)' => strtolower($slug)), 'fields' => 'id'));
		return $user['User']['id'];
	}

	function getProfile($slug, $arguments = false){
		$defaults = Configure::read('UserInfo');
		//parse the options
		$options = $this->parseArguments($defaults, $arguments);

		//get the profile
		$this->Behaviors->attach('Containable');

		$user = $this->find('first', array(
			'conditions' => array(
				'lower(slug)' => strtolower($slug)
			),
			'contain' => array(
				'Friend' => array(
					'limit' => $options['friends']['limit'],
					'order' => $options['friends']['order'],
					'User.Profile',
				),
				'Media',
				'Profile',
			)
		));
		return $user;
	}
	
	function makeUserDir($id) {
		$baseDir = APP . 'users' . DS;
		$home = rand(0, 31000) . DS;
		$sub = rand(0, 31000) . DS;
		
		$userHome = $baseDir . $home . $sub . $id . DS;
		if (mkdir($userHome, 0777, true)) {
			return array('home' => $home, 'sub' => $sub);
		} else {
			return false;
		}
	}
}

