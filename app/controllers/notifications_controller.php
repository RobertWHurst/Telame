<?php
class NotificationsController extends AppController {

	function beforeFilter() {
		parent::beforeFilter();
	}

	function beforeRender(){
		parent::beforeRender();

		//set the layout
		$this->layout = 'tall_header_w_sidebar';
	}


	function index() {
		$notifications = $this->Notification->getAllNotifications($this->currentUser['User']['id']);

		$this->set(compact('notifications'));
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