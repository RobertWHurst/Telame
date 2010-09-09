<?php
class SettingsController extends AppController{

	var $helpers = array('Acl');
	var $uses = array();

	function beforeRender() {
		parent::beforefilter();

		//set the layout
		$this->layout = 'tall_header_w_sidebar';

		$user = $this->currentUser;

		$this->set(compact('user'));
	}

	function basic() {

	}

	function friends() {
		$this->loadModel('GroupsUser');
		$friends = $this->GroupsUser->getFriends(array('uid' => $this->currentUser['User']['id']));
		$this->set(compact('friends'));
	}

	function groups() {
		$this->loadModel('Group');
		$groups = $this->Group->getFriendLists(array('uid' => $this->currentUser['User']['id']));
		$this->set(compact('groups'));
	}

	function permissions($selectedFriendList = 0) {
		if(empty($this->data)) {
			$this->loadModel('Group');

			//get the current user and note their id.
			$uid = $this->currentUser['User']['id'];

			//get the current user's acl data.
			$acoTree = $this->Aacl->getAcoTree($uid);

			$this->set(compact('acoTree'));
		} else {
			if ($this->Aacl->saveAco($this->data)){
				$this->Session->setFlash(__('permissions_saved', true), array('class' => 'info'));
			} else {
				$this->Session->setFlash(__('permissions_not_saved', true), array('class' => 'warning'));
			}

			$this->redirect($this->here);
			exit;
		}
	}

	function profile() {

		//get the user id
		$id = $this->currentUser['User']['id'];

		if(empty($this->data)){
			//save the users new settings

			//TODO: ACL STUFF HERE

		}

		//get the users current settings

		//TODO: ACL STUFF HERE
	}

}