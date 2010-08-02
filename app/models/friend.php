<?php
class Friend extends AppModel{
	var $name = 'Friend';
	var $belongsTo = array(
		'User' => array(
			'className' => 'User',
			'foreignKey' => 'parent_user_id'
	));

	function getFriendList($uid) {
		return $this->find('list', array(
						'conditions' => array(
							'parent_user_id' => $uid,
						),
						'fields' => array(
							'child_user_id'
						)
					)
				);
	}
}