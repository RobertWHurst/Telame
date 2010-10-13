<?php
class GroupsController extends AppController {

	function beforeFilter(){
		parent::beforeFilter();
	}

	function beforeRender() {
		parent::beforefilter();
	}

	public function addGroup() {
		$this->Group->create();
		$this->data['Group']['user_id'] = $this->currentUser['User']['id'];
		if ($this->Group->save($this->data)) {
			$this->Session->setFlash(__('group_added', true));
		} else {
			$this->Session->setFlash(__('group_not_added', true));
		}
		$this->redirect($this->referer());
	}

	function deleteGroup($id) {
		$group = $this->Group->findById($id);
		// get the group
		// ensure the person is the groups owner
		if($group['Group']['user_id'] != $this->currentUser['User']['id']) {
			$this->Session->setFlash(__('group_not_allowed_delete', true));
		} elseif($this->Group->delete($id)) {
			$this->Session->setFlash(__('group_deleted', true));
		} else {
			$this->Session->setFlash(__('group_not_deleted', true));
		}
		$this->redirect($this->referer());
	}

}