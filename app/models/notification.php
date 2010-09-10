<?php
class Notification extends AppModel {
	var $name = 'Notification';
	var $belongsTo = array(
		'User' => array(
			'className' => 'User',
			'foreignKey' => 'user_id',
			'counterCache' => true,
			'counterScope' => array('Notification.new' => true)
	));

	function addNotification($uid, $type, $title, $content) {
		$notifications = array(
			'friend',
			'pm',
		);

		if (!in_array($type, $notifications)) {
			return false;
		}

		$this->create();
		$this->data['Notification']['title'] = Sanitize::clean($title);
		$this->data['Notification']['content'] = Sanitize::clean($content);
		$this->data['Notification']['type'] = $type;
		$this->data['Notification']['user_id'] = $uid;
		$this->data['Notification']['new'] = true;

		if (!$this->save($this->data)) {
			return false;
		}
		return true;
	}

	function getAllNotifications($uid) {
		$this->recursive = -1;
		return $this->find('all', array(
				'conditions' => array(
					'user_id' => $uid
				),
				'order' => array(
					'Notification.new',
					'Notification.id'
				)
			)
		);
	}

	function updateCount($uid) {
		$count = $this->find('count', array('conditions' => array('Notification.user_id' => $uid, 'Notification.read' => null)));
		$this->User->id = $uid;
		$this->User->saveField('notification_count', $count);
	}

}