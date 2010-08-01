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
    
   	function afterFind($results) {
   		if (isset($results['id']) && isset($results['email'])) {
   			$this->id = $results['id'];
   			$results['first_name'] = ucwords($this->getMeta('first_name'));
   			$results['last_name'] = ucwords($this->getMeta('last_name'));
   			$results['full_name'] = ucwords($this->getMeta('first_name') . ' ' . $this->getMeta('last_name'));
   		}
		return $results;
	}
    
    //TODO: needs containable.
    function getWallPosts($limit = 0, $offset = 0, $id = false){
		
		//get the profile
		$this->recursive = 2;
		
		if($id){
			$conditions = array(
				'user_id' => $id
			);
		}
		else{
			$conditions = array();
		}
		
		
		$wallPosts = $this->find('all', array(
			'conditions' => $conditions,
			'limit' => $limit,
			'offset' => $offset,
			'order' => 'WallPost.id DESC',
		));
		return $wallPosts;
    }
}