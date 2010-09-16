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

	function addNotification($uid, $type, $title, $content, $mid) {
		$notifications = array(
			'groups_user',
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
		$this->data['Notification']['model_id'] = true;

		if (!$this->save($this->data)) {
			return false;
		}
		return true;
	}

	function getAllNotifications($uid) {
		$this->recursive = -1;
		$note = $this->find('all', array(
				'conditions' => array(
					'user_id' => $uid
				),
				'order' => array(
					'Notification.new',
					'Notification.id'
				)
			)
		);
		// FIXME
		// This is hard coded to always return 'user', not necessarilly the best
		foreach ($note as $key => $val) {
			$type = Inflector::classify($val['Notification']['type']);
			App::Import('Model', $type);
			$this->$type = new $type;
		
			$data = $this->$type->find('first', array(
				'conditions' => array(
					$type . '.id' => $val['Notification']['model_id'],
				),
				'contain' => array(
					'User',
				)
			));
		
			$note[$key]['User'] = $data['User'];
		}
		return $note;
	}

	function markRead($nid, $uid) {
		$this->recursive = -1;
		$note = $this->findById($nid);
		if ($note['Notification']['user_id'] != $uid) {
			return false;
		} else {
			$this->id = $nid;
			$this->saveField('new', false);
			return true;
		}
	}

	function updateCount($uid) {
		$count = $this->find('count', array('conditions' => array('Notification.user_id' => $uid, 'Notification.read' => null)));
		$this->User->id = $uid;
		$this->User->saveField('notification_count', $count);
	}

}