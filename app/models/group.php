<?php
class Group extends AppModel {

	var $hasAndBelongsToMany = array('User');


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