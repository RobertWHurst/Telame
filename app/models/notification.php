<?php
class Notification extends AppModel {
	var $name = 'Notification';
	var $belongsTo = array('User');

	function getAllNotifications() {
		$this->Notification->recursive = -1;
		return $this->find('all', array(
				'conditions' => array(
					'user_id' => Configure::read('UID')
				)
			)
		);
	}
}