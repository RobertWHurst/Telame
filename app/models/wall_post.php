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
	
	//TODO: needs containable.
	function getWallPosts($limit = 0, $offset = 0, $id = false){
		
		//get the profile
		$this->Behaviors->attach('Containable');

		if($id) {
			$conditions = array(
				'user_id' => $id
			);
		} else {
			$conditions = array();
		}
		
		
		$wallPosts = $this->find('all', array(
			'conditions' => $conditions,
			'contain' => array(
				'PostAuthor'
			),
			'limit' => $limit,
			'offset' => $offset,
			'order' => 'WallPost.id DESC',
		));
		return $wallPosts;
	}
}