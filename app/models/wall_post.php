<?php
class WallPost extends AppModel {
	public $belongsTo = array(
		'User' => array(
			'className' => 'User',
			'foreignKey' => 'user_id'
		),
		'PostAuthor' => array(
			'className' => 'User',
			'foreignKey' => 'author_id'
		)
	);
	public $hasMany = array(
		'Replies' => array(
			'className' => 'WallPost',
			'foreignKey' => 'reply_parent_id',
			'dependent' => true,
			'order' => 'posted ASC'
		),
		'WallPostLike'
	);

	private $aid = false;
	private $currentUserId = false;

// -------------------- Callback functions
	function afterSave() {
		$rootPerms = array();

		App::import('Component', 'Acl');
		$this->Acl = new AclComponent();

		// find the root user
		$this->Acl->Aco->recursive = -1;
		$aco = $this->Acl->Aco->find('first', array('conditions' => array('alias' => 'User::' . $this->data['WallPost']['author_id'])));

		// find the wallpost sub
		$this->Acl->Aco->recursive = 1;
		$aco = $this->Acl->Aco->find('first', array(
			'conditions' => array(
				'alias' => 'wallposts',
				'lft >' => $aco['Aco']['lft'],
				'rght <' => $aco['Aco']['rght'],
			)
		));

		foreach ($aco['Aro'] as $perm) {
			$permission = ($perm['Permission']['_read'] ? 1 : 0);
			$rootPerms[$perm['foreign_key']] = $permission;
		}

		$acoData = array(
			'parent_id' => $aco['Aco']['id'],
			'alias' => 'WallPost::' . $this->id,
			'model' => 'WallPost',
			'foreign_key' => $this->id,
		);

		$this->Acl->Aco->create($acoData);
		$this->Acl->Aco->save();
	}

	function beforeFind($data) {
		if (!$this->aid) {
			return $data;
		}
		App::import('Component', 'Acl');
		$this->Acl = new AclComponent();

		App::import('Model', 'GroupsUser');
		$this->GroupsUser = new GroupsUser();

		// make it an array if it's not already
		if ($this->aid && !is_array($this->aid)) {
			$this->aid = array($this->aid);
		}

		// remove ourself from the author id array
		$key = array_search($this->currentUserId, $this->aid);
		if($key !== false) {
			unset($this->aid[$key]);
		}

		foreach ($this->aid as $aid) {
			$group = $this->GroupsUser->listGroups($this->currentUserId, $aid);

			$aro = $this->Acl->Aro->find('first', array(
				'conditions' => array(
					'model' => 'User',
					'foreign_key' => $aid,
				)
			));

			// find the root author user
			$this->Acl->Aco->recursive = -1;
			$rootUserAco = $this->Acl->Aco->find('first', array('conditions' => array('alias' => 'User::' . $aid)));

			// wallpost aco id
			$this->Acl->Aco->recursive = 1;
			$rootWpAco = $this->Acl->Aco->find('first', array(
				'conditions' => array(
					'alias' => 'wallposts',
					'lft >' => $rootUserAco['Aco']['lft'],
					'rght <' => $rootUserAco['Aco']['rght'],
				)
			));

			// if left is one less than right, they have no specific permissions
			if ($rootWpAco['Aco']['lft'] != ($rootWpAco['Aco']['rght'] + 1)) {
				$this->Acl->Aco->recursive = 1;
				$wallPostsAcos = $this->Acl->Aco->find('all', array(
					'conditions' => array(
						'lft >' => $rootWpAco['Aco']['lft'],
						'rght <' => $rootWpAco['Aco']['rght'],
					)
				));
			}

			$rootPermissions = Set::extract('/Aro[foreign_key=' . $group['GroupsUser']['group_id'] . ']/Permission/_read', $rootWpAco);

			if(empty($rootPermissions) || !$rootPermissions[0]) {
				$key = array_search($aid, $data['conditions']['OR']['WallPost.author_id']);
				unset($data['conditions']['OR']['WallPost.author_id'][$key]);
			}
		}
		return $data;
	}

// -------------------- Other functions
	public function add($data, $args = false) {
		$defaults = array(
			'type' => 'post',
			'class' => 'wall_post'
		);
		$options = parseArguments($defaults, $args);

		$this->create();

		//save the post content and time
		$data['WallPost']['post'] = Sanitize::clean($data['WallPost']['post']);
		$data['WallPost']['posted'] = date("Y-m-d H:i:s");
		$data['WallPost']['type'] = $options['type'];
		$data['WallPost']['class'] = $options['class'];

		$this->save($data);
	}

	//TODO: needs containable.
	public function getWallPosts($currentUserId, $arguments = false) {
		$this->currentUserId = $currentUserId;

		$conditions = array();
		//set the default options
		$defaults = array(
			//post id
			'id' => false,
			//user id
			'uid' => false,
			//author id
			'aid' => false,
			//blocked user id
			'buid' => false,
			//blocked author id
			'baid' => false,
			//include the user data
			'User' => true,
			///include the post author data
			'PostAuthor' => true,
			//??? don't touch ???
			'single' => false,
			//max mumber of posts returned
			'limit' => 20,
			//number of posts to skip
			'offset' => 0,
			//filter by post type
			'type' => false
		);

		//parse the options
		$options = parseArguments($defaults, $arguments, true);

		// create conditions
		// get only get parents in the top level, not replies.
		if($options['single'] == false) {
			$conditions['reply_parent_id'] = null;
		}
		if($options['id']) {
			$conditions['WallPost.id'] = $options['id'];
		}
		if($options['uid']) {
			$conditions['OR']['WallPost.user_id'] = $options['uid'];
		}
		if($options['aid']) {
			$conditions['OR']['WallPost.author_id'] = $options['aid'];
			$this->aid = $options['aid'];
		}
		if($options['buid']) {
			$conditions['WallPost.user_id <>'] = $options['buid'];
		}
		if($options['baid']) {
			$conditions['WallPost.author_id <>'] = $options['baid'];
		}
		if($options['type']){
			$conditions['WallPost.type'] = $options['type'];
		}

		//create the contain rules
		if($options['User']) {
			$contain[] = 'User';
			$contain[] = 'Replies.User';
		}
		if($options['PostAuthor']) {
			$contain[] = 'PostAuthor';
			$contain[] = 'Replies.PostAuthor';
		}
		$contain[] = 'WallPostLike.User';

		if ($options['single']) {
			$type = 'first';
			$options['limit'] = 1;
		} else {
			$type = 'all';
		}

		$this->recursive = 2;
		$wallPosts = $this->find($type, array(
			'conditions' => $conditions,
			'contain' => $contain,
			'limit' => $options['limit'],
			'offset' => $options['offset'],
			'order' => 'posted DESC'
		));

		// This needs fixing
		// FIXME
		if ($options['single']) {
			foreach ($wallPosts['WallPost'] as $key => $val) {
				$wallPosts[$key] = $val;
			}
		}
		$count = count($wallPosts);
		for ($i=0; $i<$count; $i++) {
			if (isset($wallPosts[$i]) && !is_null($wallPosts[$i]['WallPost']['model_id'])) {
				$model = Inflector::classify($wallPosts[$i]['WallPost']['type']);
				App::Import('Model', $model);
				$this->$model = new $model;
				$this->$model->recursive = -1;
				$data = $this->$model->findById($wallPosts[$i]['WallPost']['model_id']);

				$wallPosts[$i][$model] = $data[$model];

			}
		}

		return $wallPosts;
	}

	public function remove($id) {
		App::import('Component', 'Acl');
		$this->Acl = new AclComponent();

		// find the root user
		$this->Acl->Aco->recursive = -1;
		$aco = $this->Acl->Aco->find('first', array('conditions' => array('alias' => 'WallPost::' . $id)));

		$this->Acl->Aco->delete($aco['Aco']['id']);
		$this->deleteAll(array('WallPost.reply_parent_id' => $id));
		$this->delete($id);
	}
}