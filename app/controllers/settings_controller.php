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

		//get the user id
		$id = $this->currentUser['User']['id'];

		if(empty($this->data)){
			//save the users new settings

			//TODO: ACL STUFF HERE

		}

		//get the users current settings

		//TODO: ACL STUFF HERE
	}

	function permissions() {
		if(empty($this->data)) {
			$uid = $this->currentUser['User']['id'];

			$acoTree = $this->Aacl->getAcoTree($uid);

			$this->set(compact('acoTree', 'groups'));
		} else {
			if ($this->Aacl->saveAco($this->data)) {
				$this->Session->setFlash(__('permissions_saved', true));
			} else {
				$this->Session->setFlash(__('permissions_not_saved', true));
			}

			$this->redirect($this->referer());
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