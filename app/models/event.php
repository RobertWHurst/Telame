<?php
class Event extends AppModel {

	var $belongsTo = array('User');

	function addEvent($uid, $data) {
			//Create and save the new event in the table.
			//Event type is set to editable - because this is a user event.
			$this->create();
			$data['Event']['title'] = Sanitize::paranoid($data['Event']['title'], array('!', '\'', '?', '_', '.', ' ', '-'));
			if (!isset($data['Event']['editable'])) {
				$data['Event']['editable'] = true;
			}
			$data['Event']['user_id'] = $uid;
			if ($this->save($data)) {
				return true;
			} else {
				return false;
			}
	}

	function getEvents($start, $end, $uid) {
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

	function isOwner($eid, $uid) {
		$event = $this->findById($eid);
		if ($event['Event']['user_id'] == $uid) {
			return true;
		} else {
			return false;
		}
	}

}