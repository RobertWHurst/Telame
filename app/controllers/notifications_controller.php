<?php
class NotificationsController extends AppController {

	public function beforeFilter() {
		parent::beforeFilter();
	}

	public function beforeRender(){
		parent::beforeRender();

		//set the layout
		$this->layout = 'tall_header_w_sidebar';
	}


	public function index() {
		$notifications = $this->Notification->getAllNotifications($this->currentUser['User']['id']);

		$this->set(compact('notifications'));
	}

	public function markRead($id) {
		if (!$id) {
			$this->redirect('/');
			exit;
		}
		// get the message
		$this->read(null, $id);
		// set "new" to false
		$this->set('new', false);
		// save it
		$this->save();
		// update notification count
		$this->Notification->updateCounter($this->currentUser['User']['id']);
	}

}