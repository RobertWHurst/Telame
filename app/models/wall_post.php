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
		)
	);
	var $hasMany = array(
		'Replies' => array(
			'className' => 'WallPost',
			'foreignKey' => 'reply_parent_id'
		)
	);
	
	//TODO: needs containable.
	function getWallPosts($limit = 0, $offset = 0, $arguments = false){
		
		//set the default options
		$defaults = array(
			'id' => false,
			'uid' => false,
			'aid' => false,
			'User' => false,
			'PostAuthor' => true,
			'Replies' => true
		);
		
		//parse the options
		$options = $this->parseArguments($defaults, $arguments, true);
		$options = $this->parseArguments($defaults, $arguments, true);
		
		//get the profile
		$this->Behaviors->attach('Containable');

		// create conditions
		// get only get parents in the top level, not replies.
		$conditions['reply_parent_id'] = null;
		
		if($options['id'])
			$conditions['WallPost.id'] = $options['id'];
		if($options['uid'])
			$conditions['WallPost.user_id'] = $options['uid'];
		if($options['aid'])
			$conditions['WallPost.author_id'] = $options['aid'];
		
		//create the contain rules
		if($options['User'])
			$contain[] = 'User.Profile';
		if($options['PostAuthor'])
			$contain[] = 'PostAuthor.Profile';
		if($options['Replies'])
			$contain[] = 'Replies.PostAuthor.Profile';
		
		$this->recursive = 2;
		$wallPosts = $this->find('all', array(
			'conditions' => $conditions,
			'contain' => $contain,
			'limit' => $limit,
			'offset' => $offset,
			'order' => 'WallPost.id DESC'
		));
		
		return $wallPosts;
	}
}