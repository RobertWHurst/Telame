<?php
App::import('Sanitize');
class User extends AppModel {

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
		'GroupsUser' => array(
			'dependent'=> true,
		),
		'Media' => array(
			'dependent' => true,
		),
		'Message' => array(
			'order' => 	'Message.created ASC',
			'dependent' => true
		),
		'Notification' => array(
			'dependent'=> true,
		),
		'WallPost' => array(
			'order' => 	'WallPost.id DESC',
			'exclusive' => true,
			'dependent'=> true,
		),
		'WallPostLike' => array(
			'dependent' => true,
		)
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
			'unique' => array(
				'rule' => 'isUnique',
				'allowEmpty' => false,
				'required' => true,
				'message' => 'That username is already in use',
			),
			'alphanumeric' => array(
				'rule' => 'alphaNumeric',
				'message' => 'Your username can only contain letters and numbers',
			)
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

	function confirm($email, $hash) {
		$user = $this->find('first', array('conditions' => array('email' => $email, 'hash' => $hash)));
		if (!$user) {
			return false;
		} else {
			$this->id = $user['User']['id'];
			$this->saveField('hash', null);
			return true;
		}
	}

	function createAcl($uid) {
		App::import('Component', 'Acl');
		$this->Acl = new AclComponent();
		$this->Acl->startup($controller);

		$node = $this->Acl->Aco->node('Users');
		$parentId = Set::extract($node, "0.Aco.id");

		$this->Acl->Aco->create(array('parent_id' => $parentId, 'alias' => 'User::' . $uid));
		$this->Acl->Aco->save();

		$node = $this->Acl->Aco->node('User::' . $uid);
		$parentId = Set::extract($node, "0.Aco.id");

		foreach (Configure::read('UserAcls') as $acl) {
			$this->Acl->Aco->create(array('parent_id' => $parentId, 'alias' => $acl));
			$this->Acl->Aco->save();
		}
		return true;
	}

// almost all related db info should be deleted automatically
// aco's 
// files
// groups

	function delete($uid) {
		$uid = intval($uid);
		$this->id = $uid;
		$this->recursive = -1;
		$user = $this->findById($uid);
		
		$userDir = USER_DIR . $user['User']['home_dir'] . DS . $user['User']['sub_dir'];
		$this->removeUserDir($userDir);
		// TODO; finish removing the rest of the user info
		
		// Remove the user and all dependent data
		$this->delete($uid, true);
	}

	function getIdFromSlug($slug){
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

	function signup($data) {
		$error = array();
		// create a new user
		$this->create();
		// fill array with data we need in the db
		$data['User']['added'] = date('Y-m-d');
		$data['User']['accessed'] = date('Y-m-d');
		$data['User']['level'] = '1';
		$data['User']['invisible'] = false;
		$data['User']['slug'] = $data['User']['slug'];
		$data['User']['type'] = '1';
		$data['User']['searchable'] = true;
		$data['User']['avatar_id'] = '-1';
		$data['User']['active'] = false;

		// save the user
		if (!$this->save(Sanitize::clean($data))) {
			$error['User']['new_user'] = 'Could not create new user';
		}

		// Save the user id, we'll need it later
		$this->recursive = -1;
		$user = $this->find('first', array('conditions' => array('email' => $data['User']['email']), 'fields' => array('id')));
		$uid = $user['User']['id'];

		// make their home directory structure
		$dir = $this->makeUserDir($this->id);
		if ($dir != false) {
			$this->saveField('home_dir', $dir['home']);
			$this->saveField('sub_dir', $dir['sub']);
		} else {
			$error['User']['user_dir'] = 'Could not create user directory';
		}

		unset($data);
		$this->Album->create();
		$data['Album']['title'] = 'Profile Pictures';
		$data['Album']['description'] = 'User\'s Profile Pictures';
		$data['Album']['user_id'] = $uid;
		$data['Album']['slug'] = 'profile_pictures';

		if (!$this->Album->save($data)) {
			$error['User']['album_error'] = 'Could not create album';
		}

		if (!$this->createAcl($uid)) {
			$error['User']['acl'] = 'ACL error';
		}

		if (count($error)) {
			debugger::log($error['User']);
			return false;
		} else {
			return true;
		}
	}

	// takes a user id and makes them a random directory, returns the dir in an array, or false if it doesn't work
	function makeUserDir($id) {
		$baseDir = USER_DIR;
		$home = rand(0, 31500) . DS;
		$sub = rand(0, 31500) . DS;

		$userHome = $baseDir . $home . $sub . $id . DS;
		if (mkdir($userHome, 0777, true)) {
			return array('home' => $home, 'sub' => $sub);
		} else {
			return false;
		}
	}
	
	
 	function removeUserDir($dir){
		if(is_file($dir)) {
			return @unlink($dir);
		} elseif(is_dir($dir)) {
			$scan = glob(rtrim($dir,'/').'/*');
			foreach($scan as $index=>$path) {
				recursiveDelete($path);
			}
			return @rmdir($dir);
		}
	}
}

