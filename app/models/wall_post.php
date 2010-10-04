<?php
class WallPost extends AppModel {
	var $belongsTo = array(
		'User' => array(
			'className' => 'User',
			'foreignKey' => 'user_id'
		),
		'PostAuthor' => array(
			'className' => 'User',
			'foreignKey' => 'author_id'
		),
		'ActionRecipient' => array(
			'className' => 'User',
			'foreignKey' => 'action_recipient_id'			
		)
	);
	var $hasMany = array(
		'Replies' => array(
			'className' => 'WallPost',
			'foreignKey' => 'reply_parent_id',
			'dependent' => true
		),
		'WallPostLike'
	);

	function add($data, $args = false) {
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
	function getWallPosts($arguments = false){

		//set the default options
		$defaults = array(
			'id' => false,
			'uid' => false,
			'aid' => false,
			'User' => false,
			'PostAuthor' => true,
			'ActionRecipient' => true,
			'single' => false,
			'limit' => 20,
			'offset' => 0
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
			$conditions['WallPost.user_id'] = $options['uid'];
		}
		if($options['aid']) {
			$conditions['OR'] = array(
				'WallPost.author_id' => $options['aid'],
				'WallPost.user_id' => $options['uid'],
			);
			unset($conditions['WallPost.user_id']);
		}

		//create the contain rules
		if($options['User']) {
			$contain[] = 'User';
		}
		if($options['PostAuthor']) {
			$contain[] = 'PostAuthor';
		}
		if($options['ActionRecipient']) {
			$contain[] = 'ActionRecipient';
		}

		$contain[] = 'Replies.PostAuthor';
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

	function remove($id) {
		$this->deleteAll(array('WallPost.reply_parent_id' => $id));
		$this->delete($id);
	}

}