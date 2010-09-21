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
		$this->loadModel('User');
		// Default page
		if (empty($this->data)) {
			$this->loadModel('Country');

			//get the gallery pos data
			$galleryPosData = unserialize($this->currentUser['Profile']['gallery_pos_data']);
			$countries = $this->Country->getList();

			$this->set(compact('countries', 'galleryPosData'));
		} // user has changed profile, save info
		else {
			$this->User->Profile->id = $this->currentUser['Profile']['id'];
			if ($this->User->Profile->save(Sanitize::clean($this->data))) {
				// update their birthday
				$dob = array();
				$dob['Event']['start'] = $this->data['Profile']['dob']['year'] . '-' . $this->data['Profile']['dob']['month'] . '-' . $this->data['Profile']['dob']['day'] . ' 00:00:00';
				$dob['Event']['end'] = $this->data['Profile']['dob']['year'] . '-' . $this->data['Profile']['dob']['month'] . '-' . $this->data['Profile']['dob']['day'] . ' 23:00:00';
				$dob['Event']['allday'] = '1';
				$dob['Event']['title'] = __('your_birthday', true);
				$dob['Event']['editable'] = false;
				$dob['Event']['recurring'] = true;

				$this->User->Event->removeBirthday($this->currentUser['User']['id']);
				$this->User->Event->addEvent($this->currentUser['User']['id'], $dob);

				// save thier name too
				$this->User->read(null, $this->currentUser['User']['id']);
				$this->User->set(array(
					'first_name' => Sanitize::clean($this->data['Profile']['first_name']),
					'last_name' => Sanitize::clean($this->data['Profile']['last_name'])
				));
				$this->User->save();

				$this->Session->setFlash(__('profile_settings_saved', true));
			} else {
				$this->Session->setFlash(__('profile_settings_saved', true));
			}
			$this->redirect($this->referer());
		}
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
			echo $data['id'] = (int) str_replace('image-', '', $data['id']);

			//create the serial data
			$serialData = serialize(array($data['id'] => array(
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

	function groups($gid = null) {
		$this->loadModel('Group');

		//get the group list
		$groups = $this->Group->getFriendLists(array('uid' => $this->currentUser['User']['id']));

		//get the current user and note their id.
		$uid = $this->currentUser['User']['id'];

		//create an empty permissions var
		$permissions = false;

		if($gid){
			//get the current user's acl data.
			$acoTree = $this->Aacl->getAcoTree($uid);

			//filter through the tree for useful data
			foreach($acoTree as $aco){
				foreach($aco['Groups'] as $group){
					if($group['Group']['id'] == $gid){
						$currentGroup = $group;
						$permissions[$aco['Aco']['id']] = array(
							'id' => $aco['Aco']['id'],
							'alias' => $aco['Aco']['alias'],
							'canRead' => ($group['Group']['canRead'])? 'yes' : 'no'
						);
					}
				}
			}
		}

		//set the view data
		$this->set(compact('groups', 'currentGroup', 'permissions'));
	}

	function create_group($id){
		$this->Group->create();
		$this->data['Group']['user_id'] = $this->currentUser['User']['id'];
		if($this->Group->save($this->data)){
			$this->Session->setFlash(__('group_added', true));
		}
		else{
			$this->Session->setFlash(__('group_not_added', true));
		}
		$this->redirect($this->here);
	}

	function delete_group($id){
		$group = $this->Group->findById($id);
		// get the group
		// ensure the person is the groups owner
		if($group['Group']['user_id'] != $this->currentUser['User']['id']){
			$this->Session->setFlash(__('group_not_allowed_delete', true));
		}
		elseif($this->Group->delete($id)){
			$this->Session->setFlash(__('group_deleted', true));
		}
		else{
			$this->Session->setFlash(__('group_not_deleted', true));
		}
		$this->redirect($this->referer());
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