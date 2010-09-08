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
	
	function permissions($selectedFriendList = 0) {
		if(empty($this->data)) {
			$this->loadModel('Group');
		
			//get the current user and note their id.
			$uid = $this->currentUser['User']['id'];
		
			//get the current user's acl data.
			$acoTree = $this->Aacl->getAcoTree($uid);
			
			//get the current user's lists
			$friendLists = $this->Group->getFriendLists(0, 0, array('uid' => $uid));
			$psudeoLists = array(
				'public' => array('Group' => array('title' => __('public', true), 'id' => 0))
			);
			$friendLists = array_merge($psudeoLists, $friendLists);
		
			//add selected info
			foreach($friendLists as $key => $filter){
				if($filter['Group']['id'] == $selectedFriendList) {
					$friendLists[$key]['selected'] = true;
				} else {
					$friendLists[$key]['selected'] = false;
				}
			}
			
			$this->set(compact('acoTree', 'friendLists'));
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