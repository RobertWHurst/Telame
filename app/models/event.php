<?php
class Event extends AppModel {

	public $belongsTo = array('User');

	public function add($uid, $data) {
			//Create and save the new event in the table.
			//Event type is set to editable - because this is a user event.
			$this->create();
			$data['Event']['title'] = Sanitize::paranoid($data['Event']['title'], array('!', '\'', '?', '_', '.', ' ', '-'));
			if (!isset($data['Event']['editable'])) {
				$data['Event']['editable'] = true;
			}
			$data['Event']['user_id'] = $uid;
			if ($this->save($data)) {
				return $this->id;
			} else {
				return false;
			}
	}

	public function getEvents($start, $end, $uid) {
		$this->recursive = -1;
		// Get the events corresponding to the time range
		$conditions = array(
			'Event.user_id' => $uid,
			array(
				'OR' => array(
					'Event.start BETWEEN ? AND ?' => array(
						date('Y-m-d H:i:s', $start),
						date('Y-m-d H:i:s', $end)
					),
					'AND' => array(
						'Event.start BETWEEN ? AND ?' => array(
							'0000-' . date('m-d H:i:s', $start),
							'9999-' . date('m-d H:i:s', $end),
						),
						'Event.recurring' => true,
					)
				)
			)
		);

		$events = $this->find('all', array('conditions' => $conditions));

		return $events;
	}

	public function isOwner($eid, $uid) {
		$event = $this->findById($eid);
		if ($event['Event']['user_id'] == $uid) {
			return true;
		} else {
			return false;
		}
	}

	public function removeBirthday($uid) {
		$this->recursive = -1;
		$bday = $this->find('first', array(
			'conditions' => array(
				'Event.user_id' => $uid,
				'Event.title' => 'your_birthday',
			)
		));
		
		if ($this->delete($bday['Event']['id'])) {
			return true;
		} else {
			return false;
		}
	}

}