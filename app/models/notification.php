<?php
class Notification extends AppModel {
	var $name = 'Notification';
	var $belongsTo = array('User');

	function getAllNotifications($uid) {
		$this->Notification->recursive = -1;
		return $this->find('all', array(
				'conditions' => array(
					'user_id' => $uid
				)
			)
		);
	}
}