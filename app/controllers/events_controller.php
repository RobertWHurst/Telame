<?php
class EventsController extends AppController {

	function add($allday=null, $day=null, $month=null, $year=null, $hour=null, $min=null) {
		if (empty($this->data)) {
			//Set default duration: 1hr and format to a leading zero.
			$hourPlus = intval($hour)+1;
			if (strlen($hourPlus) == 1) {
				$hourPlus = '0' . $hourPlus;
			}

			//Create a time string to display in view. The time string
			//is either	 'Fri 26 / Mar, 09 : 00 ‰ÛÓ 10 : 00' or
			//'All day event: (Fri 26 / Mar)'
			if ($allday == 'true') {
				$event['Event']['allday'] = 1;
				$displayTime = 'All day event: ('
					. date('D', strtotime($day.'/'.$month.'/'.$year)).' '.
					$day.' / '. date('M', mktime(0, 0, 0, $month, 10)).')';
			} else {
				$event['Event']['allday'] = 0;
				$displayTime = date('D', strtotime($day . '/' . $month . '/' . $year)).' '
					.$day.' / '.date('M', mktime(0, 0, 0, $month, 10)).
					', ' . $hour . ' : ' . $min . ' &mdash; ' . $hourPlus . ' : ' . $min;
			}
			$this->set('displayTime', $displayTime);

			//Populate the event fields for the add form
			$event['Event']['title'] = 'Event description';
			$event['Event']['start'] = $year . '-' . $month . '-' . $day . ' ' . $hour . ':' . $min . ':00';
			$event['Event']['end'] = $year . '-' . $month . '-' . $day . ' ' . $hourPlus . ':' . $min . ':00';
			$this->set('event', $event);

			//Do not use a view template.
			$this->layout = false;
		} else {
			if ($this->Event->addEvent($this->currentUser['User']['id'], $this->data)) {
				$this->Session->setFlash(__('event_saved', true));
			} else {
				$this->Session->setFlash(__('event_not_saved', true));
			}
			$this->redirect(array('slug' => $this->currentUser['User']['slug'], 'controller' => 'events', 'action' => 'calendar', substr($this->data['Event']['start'], 0, 4),
				substr($this->data['Event']['start'], 5, 2), substr($this->data['Event']['start'], 8, 2)));
		}
	}

	function calendar($year=null, $month=null, $day=null) {
		$this->layout = 'tall_header_w_sidebar';
		if ($year != null) {
			$this->set('openYear', $year);
			if ($month != null) {
				$month = ltrim($month, '0');
				$month = $month-1;
				$this->set('openMonth', $month);
			}
			if ($day != null){
				$day = ltrim($day, '0');
				$this->set('openDay', $day);
			}
		}
	}

	function edit($id = null) {
		if (empty($this->data)) {
			if (is_null($id)) {
				//fail gracefully in case of error
				return;
			}
			$ev = $this->Event->findById($id);
			$ev['Event']['start'] = date('Y-m-d h:i:s', strtotime($ev['Event']['start']));
			$ev['Event']['end'] = date('Y-m-d h:i:s', strtotime($ev['Event']['end']));
			$this->set('event', $ev);
			if ($ev['Event']['allday'] == '1') {
				$displayTime = 'All day event';
			} else {
				$displayTime = date('D M d, H:i', strtotime($ev['Event']['start'])) . '&mdash;' . date('H:i', strtotime($ev['Event']['end']));
			}
			$this->set('displayTime', $displayTime);
			$this->layout = false;
		} else {
			$this->Event->id = $this->data['Event']['id'];
			$this->Event->saveField('title', $this->data['Event']['title']);
			$this->redirect(array('slug' => $this->currentUser['User']['slug'], 'controller' => 'events', 'action' => 'calendar', substr($this->data['Event']['start'], 0, 4),
				substr($this->data['Event']['start'], 5, 2), substr($this->data['Event']['start'], 8, 2)));
		}
	}

	function delete($id) {
		$this->data = $this->Event->read(null, $id);
		if ($this->Event->isOwner($id, $this->currentUser['User']['id'])) {
			if ($this->Event->delete($id)) {
				$this->Session->setFlash(__('event_deleted', true));
			} else {
				$this->Session->setFlash(__('event_not_deleted', true));
			}
		} else {
			$this->Session->setFlash(__('event_not_allowed_delete', true));
		}
		$this->redirect(array('slug' => $this->currentUser['User']['slug'], 'controller' => 'events', 'action' => 'calendar', substr($this->data['Event']['start'], 0, 4),
			substr($this->data['Event']['start'], 5, 2), substr($this->data['Event']['start'], 8, 2)));
	}

	function feed() {
		$events = $this->Event->getEvents($this->params['url']['start'], $this->params['url']['end'], $this->currentUser['User']['id']);

		//Create the json array
		$rows = array();
		foreach($events as $key => $val) {
			//Is it an all day event?
			$all = ($events[$key]['Event']['allday'] == 1);

			//Create an event entry
			$rows[] = array('id' => $events[$key]['Event']['id'],
				'title' => $events[$key]['Event']['title'],
				'allDay' => $all,
				// check for recurring events
				'recurring' => $events[$key]['Event']['recurring'],
				// if the event is recurring year after year, set the display year to the year being requested, while retaining all other info
				'start' => ($events[$key]['Event']['recurring'] ? date('Y', $this->params['url']['start']) . '-' . date('m-d H:i', strtotime($events[$key]['Event']['start'])) : date('Y-m-d H:i', strtotime($events[$key]['Event']['start']))),
				// set the end year to the same as the start, because if in december, the start can be one year, and the end +1
				'end' => ($events[$key]['Event']['recurring'] ? date('Y', $this->params['url']['start']) . '-' . date('m-d H:i', strtotime($events[$key]['Event']['end'])) : date('Y-m-d H:i', strtotime($events[$key]['Event']['end']))),
				'editable' => $events[$key]['Event']['editable'],
			);
		}

		// Return as a json array
		Configure::write('debug', 0);
		$this->autoRender = false;
		$this->autoLayout = false;
		$this->header('Content-Type: application/json');
		echo json_encode($rows);
	}

	function move($id = null, $dayDelta, $minDelta, $allDay) {
		if ($id != null) {
			$this->Event->recursive = -1;
			$ev = $this->Event->findById($id);  //1 - locate the event in the DB
			if ($allDay == 'true') { //2- handle all day events
				$ev['Event']['allday'] = 1;
			} else {
				$ev['Event']['allday'] = 0;
			}
			//3 - Start
			$ev['Event']['end'] = date('Y-m-d H:i:s', strtotime('' . $dayDelta . ' days ' . $minDelta . ' minutes', strtotime($ev['Event']['end'])));
			$ev['Event']['start'] = date('Y-m-d H:i:s', strtotime('' . $dayDelta . ' days ' . $minDelta . ' minutes', strtotime($ev['Event']['start'])));

			$this->Event->save($ev); //4 - Save the event with the new data
			//5 - redirect and reload
			$this->redirect(array('slug' => $this->currentUser['User']['slug'], 'controller' => 'events', 'action' => 'calendar', substr($ev['Event']['start'], 0, 4), substr($ev['Event']['start'], 5, 2), substr($ev['Event']['start'], 8, 2)));
		}
	}

	function resize($id = null, $dayDelta, $minDelta) {
		if ($id != null) {
			$ev = $this->Event->findById($id);
			$ev['Event']['end'] = date('Y-m-d H:i:s', strtotime('' . $dayDelta . ' days ' . $minDelta . ' minutes', strtotime($ev['Event']['end'])));
			$this->Event->save($ev);
		}
		$this->redirect(array('slug' => $this->currentUser['User']['slug'], 'controller' => 'events', 'action' => 'calendar', substr($ev['Event']['start'], 0, 4), substr($ev['Event']['start'], 5, 2), substr($ev['Event']['start'], 8, 2)));
	}

}