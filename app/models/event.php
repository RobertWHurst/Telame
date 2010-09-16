<?php
class Event extends AppModel {

	var $belongsTo = array('User');

	function getEvents($start, $end, $uid) {
		// Get the events corresponding to the time range
		$conditions = array('Event.start BETWEEN ? AND ?' => array(date('Y-m-d H:i:s', $start), date('Y-m-d H:i:s', $end)), 'Event.user_id' => $uid);
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