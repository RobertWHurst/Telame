<?php
class Group extends AppModel {

	// Connect to the ACL table
	var $actsAs = array('Acl' => array('type' => 'requester'));

	var $hasAndBelongsToMany = array('User');

// -------------------- ACL functions
	function parentNode() {
		return null;
	}

	function getFriendLists($limit = 0, $offset = 0, $arguments = false){
		$defaults = array(
			'uid' => false
		);
		
		$options = $this->parseArguments($defaults, $arguments);
		
		$conditions = array(
			'user_id' => array($options['uid'], '-1')
		);
		
		$this->recursive = -1;
		return $this->find('all', array(
			'limit' => $limit,
			'offset' => $offset,
			'conditions' => $conditions,
			'order' => 'lower(title)',
		));
	}

}