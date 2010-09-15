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

	function basic(){
		$this->loadModel('User');
		
		//get the gallery pos data
		$galleryPosData = unserialize($this->currentUser['Profile']['gallery_pos_data']);
		
		$this->set(compact('galleryPosData'));
		
	}

	function gallery($id = null, $top = null, $left = null, $height = null, $width = null){
		$this->loadModel('User');
		
		$data = compact('id', 'top', 'left', 'height', 'width');
		
		//if the request is not ajax redirect to basics as this control set is pressent there.	
		if(!$this->RequestHandler->isAjax())
			$this->redirect(array('controller' => 'settings', 'action' => 'basic'));
	
		$this->layout = false;
		
		if(!empty($data)){
			
			//fix the id
			$data['id'] = (int) str_replace('image-', '', $data['id']);
			
			//create the serial data
			$serialData = serialize(array(array(
				'id' => $data['id'],
				'x' => $data['left'],
				'y' => $data['top'],
				'h' => $data['height'],
				'w' => $data['width']
			)));
			
			$this->User->Profile->save(array(
				'id' => $this->currentUser['Profile']['id'],
				'user_id' => $this->currentUser['User']['id'],
				'gallery_pos_data' => $serialData
			));
			
			exit();
		}
		else{		
			//$this->render('element/settings/gallery_controls');
		}	
	}

	function friends(){
		$this->loadModel('GroupsUser');
		$friends = $this->GroupsUser->getFriends(array('uid' => $this->currentUser['User']['id']));
		$this->set(compact('friends'));
	}

	function groups($id = null) {
		$this->loadModel('Group');
		// we're loading the page to view the groups, or to delete a group
		if (empty($this->data)) {
			// if ID is not null, we're deleting
			if (is_null($id)) {
				$groups = $this->Group->getFriendLists(array('uid' => $this->currentUser['User']['id']));
				$this->set(compact('groups'));
			} else { // delete block
				// get the group
				$group = $this->Group->findById($id);
				// ensure the person is the groups owner
				if ($group['Group']['user_id'] != $this->currentUser['User']['id']) {
					$this->Session->setFlash(__('group_not_allowed_delete', true));
				} elseif ($this->Group->delete($id)) {
					$this->Session->setFlash(__('group_deleted', true));
				} else {
					$this->Session->setFlash(__('group_not_deleted', true));
				}
				$this->redirect($this->referer());
			}
		} else {
			$this->Group->create();
			$this->data['Group']['user_id'] = $this->currentUser['User']['id'];
			if ($this->Group->save($this->data)) {
				$this->Session->setFlash(__('group_added', true));
			} else {
				$this->Session->setFlash(__('group_not_added', true));
			}
			$this->redirect($this->here);
		}
	}

	function permissions($selectedFriendList = 0) {
		if(empty($this->data)) {
			//get the current user and note their id.
			$uid = $this->currentUser['User']['id'];

			//get the current user's acl data.
			$acoTree = $this->Aacl->getAcoTree($uid);

			$this->set(compact('acoTree'));
		} else {
			if ($this->Aacl->saveAco($this->data)){
				$this->Session->setFlash(__('permissions_saved', true), 'default', array('class' => 'info'));
			} else {
				$this->Session->setFlash(__('permissions_not_saved', true), 'default', array('class' => 'warning'));
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