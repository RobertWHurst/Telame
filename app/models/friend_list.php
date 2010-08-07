<?php
class FriendList extends AppModel {
	
	var $hadMany = array(
		'Friend' => array(
			'className' => 'Friend',
			'foreignKey' => 'list_id'
		)
	);
	
	var $belongsTo = array(
		'User' => array(
			'className' => 'User',
			'foreignKey' => 'user_id'
		)
	);
	
	function getFriendLists($limit = 0, $offset = 0, $arguments = false){
		$defaults = array(
			'uid' => false
		);
		
		$options = $this->parse_arguments($defaults, $arguments);
		
		$conditions = array(
			'User.id' => $options['uid']
		);
		
		return $this->find('all', array(
			'limit' => $limit,
			'offset' => $offset,
			'conditions' => $conditions
		));
	}
}