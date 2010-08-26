<?php
class SettingsController extends AppController{

	var $uses = array();

	function beforeRender() {
		parent::beforefilter();

		//set the layout
		$this->layout = 'tall_header_w_sidebar';

		$user = $this->currentUser;

		$this->set(compact('user'));
	}

	function basic() {

		//get the user id
		$id = $this->currentUser['User']['id'];

		if(empty($this->data)){
			//save the users new settings

			//TODO: ACL STUFF HERE

		}

		//get the users current settings

		//TODO: ACL STUFF HERE
	}

	function groups() {
		$this->loadModel('Group');

		$results = array();
		$uid = $this->currentUser['User']['id'];

		$groups = $this->Group->getFriendLists(0, 0, array('uid' => $uid));
		$acoTree = $this->Aacl->getAcoTree($uid, $groups);

		$this->set(compact('acoTree', 'groups'));
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