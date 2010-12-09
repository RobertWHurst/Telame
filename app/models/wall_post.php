<?php
class WallPost extends AppModel {
	public $actsAs = array('Aacl');

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

	public $aid = false;
	public $currentUserId = false;

// -------------------- Callback functions


// -------------------- Other functions
	public function add($data, $args = false) {
		$defaults = array(
			'class' => 'wall_post'
		);
		$options = parseArguments($defaults, $args);

		$this->create();

		//save the post content and time
		$data['WallPost']['post'] = Sanitize::clean($data['WallPost']['post']);
		$data['WallPost']['posted'] = date("Y-m-d H:i:s");

		$this->save($data);
	}

	//TODO: needs containable.
	public function getWallPosts($currentUserId, $arguments = false) {
		$this->currentUserId = $currentUserId;

		$conditions = array();

		//set the default options
		$defaults = array(
			'all_friends' => false,	// show all friends
			'id' => false,			//post id
			'limit' => Configure::read('WallPost.Limit'),	//max mumber of posts returned
			'offset' => 0,			//number of posts to skip
			'uid' => false,			//user id
		);

		//parse the options
		$options = parseArguments($defaults, $arguments, true);

		// create conditions
		if($options['id']) {
			$conditions['WallPost.id'] = $options['id'];
			$postType = 'first';
		}
		// UID is for only showing posts on a certain wall
		if($options['uid']) {
			$conditions['WallPost.user_id'] = $options['uid'];
		}
		// mainly used for news feed.  make sure the author and user are both your friends
		if($options['all_friends']) {
			$conditions['AND']['WallPost.author_id'] = $options['all_friends'];
			$conditions['AND']['WallPost.user_id'] = $options['all_friends'];
			$conditions['reply_parent_id'] = null;
			$this->aid = $options['all_friends'];
		}

		//create the contain rules
		$contain[] = 'User';				// Owner - who's wall this is posted on
		$contain[] = 'PostAuthor';			// Who made the post
		$contain[] = 'Replies.PostAuthor';	// The author of any replies
		$contain[] = 'WallPostLike.User';	// The user(s) of who liked/disliked this post

		// grab as much as you can, it will however be contained
		$this->recursive = 2;
		// check if we have set $postType, if it is set, use it, otherwise just use 'all'
		$wallPosts = $this->find((isset($postType) ? $postType : 'all'), array(
			'conditions' => $conditions,
			'contain' => $contain,
			'limit' => $options['limit'],
			'offset' => $options['offset'],
			'order' => 'WallPost.posted DESC'
		));

		// this transforms the data array.  the data structure of a find all vs find first is different
		if ($options['id']) {
			foreach ($wallPosts['WallPost'] as $key => $val) {
				$wallPosts[$key] = $val;
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