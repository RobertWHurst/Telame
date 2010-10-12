<?php
class Group extends AppModel {

	// Connect to the ACL table
	public $actsAs = array('Acl' => array('type' => 'requester'));

	public $hasAndBelongsToMany = array('User');

// -------------------- ACL functions
	public function parentNode() {
		return null;
	}

	public function getFriendLists($arguments = false) {
		$defaults = array(
			'uid' => false,
			'type' => 'all',
			'limit' => 0, 
			'offset' => 0,
		);

		$options = parseArguments($defaults, $arguments);
		
		$conditions = array(
			'user_id' => array($options['uid'], '-1')
		);
		
		$this->recursive = -1;
		return $this->find($options['type'], array(
			'limit' => $options['limit'],
			'offset' => $options['offset'],
			'conditions' => $conditions,
			'order' => 'lower(title)',
		));
	}
	
	public function getGroupTitleById($id){
		$results = $this->findById($id);
		return $results['Group']['title'];
	}

}