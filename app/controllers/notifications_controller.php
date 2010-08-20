<?php
class NotificationsController extends AppController {

	function beforeFilter() {
		parent::beforeFilter();
	}

	function markRead($id) {
		if (!$id) {
			$this->redirect('/');
			exit;
		}
		$this->read(null, $id);
		$this->set('new', false);
		$this->save();
	}

}