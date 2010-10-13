<?php
App::import('Sanitize');
class User extends AppModel {

	// Connect to the ACL table
	public $actsAs = array('Acl' => array('type' => 'requester'));

	public $belongsTo = array(
		'Media' => array(
			'ClassName' => 'Media',
			'foreignKey' => 'avatar_id',
		),
	);

	public $hasAndBelongsToMany = array('Group');

	public $hasMany = array(
		'Album' => array(
			'dependent'=> true,
		),
		'Event' => array(
			'dependent' => true,
		),
		'GroupsUser' => array(
			'dependent' => true,
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

	public $hasOne = array(
		'Profile' => array(
			'dependent' => true,
		),
	);

	public $validate = array(
		'beta_key' => array(
			'usedKey' => array(
				'rule' => 'checkBetaKeyUsed',
				'message' => 'That beta key has already been used',
			),
			'validKey' => array(
				'rule' => 'checkBetaKey',
				'message' => 'That beta key is invalid',
			),
		),
		'email' => array(
			'uniqueEmail' => array(
				'rule' => array('checkUnique', 'email'),
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
		'first_name' => array(
			'required' => true,
			'allowEmpty' => false,
			'rule' => 'alphanumeric',
			'message' => 'Your first name is required',
		),
		'last_name' => array(
			'required' => true,
			'allowEmpty' => false,
			'rule' => 'alphanumeric',
			'message' => 'Your last name is required',
		),
		'slug' => array(
			'unique' => array(
				'rule' => array('checkUnique', 'slug'),
				'allowEmpty' => false,
				'required' => true,
				'message' => 'That username is already in use',
			),
			'alphanumeric' => array(
				'rule' => 'alphaNumeric',
				'message' => 'Your username can only contain letters and numbers',
			),
			'slug' => array(
				'rule' => 'checkBlacklist',
				'message' => 'That username is invalid',
			),
		),
		'user_password' => array(
			'length' => array(
				'rule' => array('minLength', 8),
				'allowEmpty' => false,
				'required' => true,
				'message' => 'Password must be 8 characters long', // to internationalize it must be done in the view
			),
		),
		'user_password_again' => array(
			'identicalFieldValues' => array(
				'rule' => array('identicalFieldValues', 'user_password'),
				'message' => 'Please re-enter your password so that the values match'
			)
		),
	);

	public function __construct($id = false, $table = null, $ds = null) {
		parent::__construct($id, $table, $ds);
		$this->virtualFields['full_name'] = sprintf('initcap(%s.first_name) || \' \' || initcap(%s.last_name)', $this->alias, $this->alias);
	}


// -------------------- Callback functions

	public function beforeFind($queryData) {
		if (isset($queryData['conditions']['User.email'])) {
			$queryData['conditions']['User.email'] = strtolower($queryData['conditions']['User.email']);
		}
		return $queryData;
	}

// -------------------- Callback functions used by the validator

	public function checkBetaKey($key) {
			App::Import('Model', 'BetaKey');
			$this->BetaKey = new BetaKey;
			// find the key they've given
			return $this->BetaKey->find('first', array('conditions' => array('key' => $this->data['User']['beta_key'])));
	}

	public function checkBetaKeyUsed($key) {
			App::Import('Model', 'BetaKey');
			$this->BetaKey = new BetaKey;
			// find the key they've given
			return $this->BetaKey->find('first', array('conditions' => array('key' => $this->data['User']['beta_key'], 'email' => $this->data['User']['email'])));
	}

	public function checkBlacklist($slug, $email) {
		//$slug will have value: array('slug' => 'username')
		return !in_array($slug['slug'], Configure::read('BlacklistUsernames'));
	}

	public function checkUnique($array, $value) {
		$what = strtolower($value);
		return !$this->find('first', array('conditions' => array('lower(User.' . $what . ')' => strtolower($array[$what]))));
	}

	public function identicalFieldValues($field=array(), $compare_field=null) {
		foreach( $field as $key => $value){
			$v1 = $value;
			$v2 = $this->data[$this->name][$compare_field];
			if($v1 !== $v2) {
				return false;
			} else {
				continue;
			}
		}
		return true;
	}
// -------------------- ACL functions
	public function parentNode() {
		return null;
	}

// --------------------- Custom functions

	public function confirm($email, $hash) {
		$user = $this->find('first', array('conditions' => array('email' => $email, 'hash' => $hash)));
		if (!$user) {
			return false;
		} else {
			$this->id = $user['User']['id'];
			$this->saveField('hash', null);
			return true;
		}
	}

// almost all related db info should be deleted automatically
// aco's
// files
// groups

	public function deleteAccount($uid) {
		$uid = intval($uid);
		$this->id = $uid;
		$this->recursive = -1;
		$user = $this->findById($uid);

		$userDir = USER_DIR . $user['User']['home_dir'] . DS . $user['User']['sub_dir'] . DS . $user['User']['id'];
		$this->removeUserDir($userDir);
		// TODO; finish removing the rest of the user info

		// Remove the user and all dependent data
		$this->delete($uid, true);
	}

	public function getIdFromSlug($slug) {
		$this->recursive = -1;
		$user = $this->find('first', array('conditions' => array('lower(slug)' => Sanitize::clean(strtolower($slug))), 'fields' => 'id'));
		return $user['User']['id'];
	}

	public function getSlugFromId($uid) {
		$this->recursive = -1;
		$user = $this->find('first', array('conditions' => array('id' => Sanitize::clean(intval($uid))), 'fields' => 'slug'));
		return $user['User']['slug'];
	}

	public function getProfile($slug, $arguments = array()) {

/*	FIXME bug in parseargs
		$defaults = array(
			'contain' => array(
				'Profile',
				'Profile.Country',
				'Notification' => array(
					'conditions' => array(
						'new' => true
					)
				)
			));

		$options = parseArguments($defaults, $arguments);
pr($options);
*/


		//get the profile
		$this->recursive = 2;
		$user = $this->find('first', array(
			'conditions' => array('lower(slug)' => Sanitize::clean(strtolower($slug))),
			'contain' => array(
				'Profile',
				'Profile.Country',
				'Profile.SO',
//				'Notification' => array(
//					'conditions' => array(
//						'new' => true
//					)
//				)
			)
		));
		return $user;
	}

	public function signup($data) {
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
		$data['User']['first_login'] = true;

		// save the user
		if (!$this->save(Sanitize::clean($data))) {
			// don't continue anything if this failed
			return false;
		}

		// no longer needed, scrap it
		unset($data);

		// make their home directory structure
		$dir = $this->makeUserDir($this->id);
		if ($dir != false) {
			$this->saveField('home_dir', $dir['home']);
			$this->saveField('sub_dir', $dir['sub']);
		} else {
			$error['User']['user_dir'] = 'Could not create user directory';
		}

		$this->Profile->create();
		$profile['Profile']['user_id'] = $this->id;
		$profile['Profile']['dob'] = '1900-01-01';
		$this->Profile->save($profile);

		$this->Album->create();
		$data['Album']['title'] = 'Profile Pictures';
		$data['Album']['description'] = __('profile_pictures', true);
		$data['Album']['user_id'] = $this->id;
		$data['Album']['slug'] = 'profile_pictures';

		if (!$this->Album->save($data)) {
			$error['User']['album_error'] = 'Could not create album';
		}

		if (count($error)) {
			debugger::log($error['User']);
			return false;
		} else {
			return $this->id;
		}
	}

	// takes a user id and makes them a random directory, returns the dir in an array, or false if it doesn't work
	public function makeUserDir($id) {
		$baseDir = USER_DIR;
		$home = rand(0, 31500);
		$sub = rand(0, 31500);

		$userHome = $baseDir . $home . DS . $sub . DS . $id . DS;
		if (mkdir($userHome, 0777, true)) {
			return array('home' => $home, 'sub' => $sub);
		} else {
			return false;
		}
	}


 	public function removeUserDir($dir){
		$scan = array();
		if(is_file($dir)) {
			return @unlink($dir);
		} elseif(is_dir($dir)) {
			$scan = glob(rtrim($dir,'/').'/*');
			foreach($scan as $index=>$path) {
				$this->removeUserDir($path);
			}
			return @rmdir($dir);
		}
	}

	public function search($search, $uid = null, $isFriend = false) {
		// Clean the params, just to be safe
		$search = Sanitize::clean(str_replace('_', ' ' ,$search));
		$this->User->recursive = -1;

		$conditions = array('searchable' => true,
			'OR' => array(
				'User.first_name ILIKE' => '%' . $search . '%',
				'User.last_name ILIKE' => '%' . $search . '%',
				'User.slug ILIKE' => '%' . $search . '%',
				'User.email ILIKE' => '%' . $search . '%',
			),
		);

		if (!is_null($uid) && $isFriend) {
			$friends = $this->GroupsUser->getFriendIds($uid);
			$conditions['User.id'] = $friends;
		}

		$users = $this->find('all', array(
			'conditions' => $conditions,
			'contain' => array(
				'Profile' => array(
					'Country'
				)
			),
			'limit' => Configure::read('PageLimit'),
			'order' => array(
				'User.first_name',
				'User.last_name',
			)
		));
		return $users;
	}
}

