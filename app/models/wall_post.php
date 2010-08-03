<?php
class WallPost extends AppModel {
	var $name = 'WallPost';
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
	function getWallPosts($limit = 0, $offset = 0, $uid = false, $aid = false, $userMeta = array()){
		//get the profile
		$this->Behaviors->attach('Containable');

		if($uid) {
			$conditions = array(
				'user_id' => $uid
			);
		} elseif($aid) {
			$conditions = array(
				'author_id' => $aid
			);
		} else {
			$conditions = array();
		}
		// only get parents, not replys
		$conditions['reply_parent_id'] = null;
		
		$this->recursive = 2;
		$wallPosts = $this->find('all', array(
			'conditions' => $conditions,
			'contain' => array(
				'PostAuthor',
				'Replies.PostAuthor'
			),
			'limit' => $limit,
			'offset' => $offset,
			'order' => 'WallPost.id DESC',
		));
		return $wallPosts;
	}
}