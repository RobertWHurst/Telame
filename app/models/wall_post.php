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
			'dependent' => true
		),
		'WallPostLike'
	);

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
	public function getWallPosts($arguments = false) {

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

			/*
			 * k wtf? doesn't this kind of defeat the purpose of uid?
			 *

			$conditions['OR'] = array(
				'WallPost.author_id' => $options['aid'],
				'WallPost.user_id' => $options['uid'],
			);
			unset($conditions['WallPost.user_id']);
			*/

			$conditions['OR']['WallPost.author_id'] = $options['aid'];
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
			'order' => 'WallPost.id DESC'
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
		$this->deleteAll(array('WallPost.reply_parent_id' => $id));
		$this->delete($id);
	}

}