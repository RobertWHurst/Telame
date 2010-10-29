<?php
class SettingsController extends AppController {

	public $components = array('Email');
	public $helpers = array('Acl');
	public $uses = array();

	public function beforeFilter(){
		parent::beforeFilter();

		$this->Security->blackHoleCallback = '_forceSSL';
		$this->Security->requireSecure('basic');
		if (!in_array($this->action, $this->Security->requireSecure) && env('HTTPS')) {
		 	$this->_unforceSSL();
		}
		$this->Auth->allow(array('confirm', 'signup'));

	}

	public function beforeRender() {
		parent::beforefilter();

		//set the layout
		$this->layout = 'tall_header_w_sidebar';
	}

	public function basic() {
		$this->loadModel('User');
		// Default page
		if (empty($this->data)) {
			$this->loadModel('Country');

			$countries = $this->Country->getList();

			$this->set(compact('countries'));
		} // user has changed profile, save info
		else {
			$this->User->Profile->id = $this->currentUser['Profile']['id'];
			if ($this->User->Profile->save(Sanitize::clean($this->data))) {
				// update their birthday
				$dob = array();
				$dob['Event']['start'] = $this->data['Profile']['dob']['year'] . '-' . $this->data['Profile']['dob']['month'] . '-' . $this->data['Profile']['dob']['day'] . ' 00:00:00';
				$dob['Event']['end'] = $this->data['Profile']['dob']['year'] . '-' . $this->data['Profile']['dob']['month'] . '-' . $this->data['Profile']['dob']['day'] . ' 23:00:00';
				$dob['Event']['allday'] = '1';
				$dob['Event']['title'] = 'your_birthday';
				$dob['Event']['editable'] = false;
				$dob['Event']['recurring'] = true;

				$this->User->Event->removeBirthday($this->currentUser['User']['id']);
				$this->User->Event->add($this->currentUser['User']['id'], $dob);

				if (!empty($this->data['Profile']['user_password']) && $this->data['Profile']['user_password'] == $this->data['Profile']['user_password_again']) {
					$userInfo = array(
					'first_name' => Sanitize::clean($this->data['Profile']['first_name']),
					'last_name' => Sanitize::clean($this->data['Profile']['last_name']),
					'first_login' => false,
					'password' => $this->Auth->password($this->data['Profile']['user_password']),
					);
					$fieldList = array('first_name', 'last_name', 'first_login', 'password');
				} else {
					$userInfo = array(
					'first_name' => Sanitize::clean($this->data['Profile']['first_name']),
					'last_name' => Sanitize::clean($this->data['Profile']['last_name']),
					'first_login' => false,
					);
					$fieldList = array('first_name', 'last_name', 'first_login');
				}

				// save thier name too
				$this->User->read(null, $this->currentUser['User']['id']);
				$this->User->set($userInfo);
				$this->User->save(null, array('fieldList' => $fieldList));

				$this->Session->setFlash(__('profile_settings_saved', true));
			} else {
				$this->Session->setFlash(__('profile_settings_saved', true));
			}
			$this->redirect($this->referer());
		}
	}

	// Delete user account
	public function delete() {
		$this->loadModel('User');
		$uid = $this->currentUser['User']['id'];

		// find groups so we can delete the aro's associtated with them
		$groups = $this->User->Group->find('all', array('conditions' => array('Group.user_id' => $uid)));

		// user info, file structure, and automatic db info
		$this->User->deleteAccount($uid);
		// acl info
		$this->Aacl->deleteAcoTree($uid, $groups);

		foreach ($groups as $group) {
			$this->User->Group->delete($group['Group']['id']);
		}

		$this->Session->setFlash(__('account_deleted', true));
		$this->AuthExtension->logout();
		$this->Auth->logout();
		$this->redirect('/');
	}

	public function gallery($id = null, $top = null, $left = null, $height = null, $width = null){

		//if the request is not ajax display the gallery options.
		if($this->RequestHandler->isAjax()) {

			$this->loadModel('User');

			$data = compact('id', 'top', 'left', 'height', 'width');

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
		else{

			//get the gallery pos data
			$galleryPosData = unserialize($this->currentUser['Profile']['gallery_pos_data']);

			$this->set(compact('galleryPosData'));
		}
	}

	public function friends(){
		$this->loadModel('GroupsUser');
		$friends = $this->GroupsUser->getFriends(array('uid' => $this->currentUser['User']['id']));
		$this->set(compact('friends'));
	}

	public function groups($gid = null) {
		if(empty($this->data)) {
			$this->loadModel('Group');

			//get the group list
			$groups = $this->Group->getFriendLists(array('uid' => $this->currentUser['User']['id']));

			//get the current user and note their id.
			$uid = $this->currentUser['User']['id'];

			//create an empty permissions var
			$permissions = $currentGroup = false;

			if ($gid) {
				//get the current user's acl data.
				$this->Group->recursive = -1;
				// find needs to be of type all or else the aacl won't know the format
				$group = $this->Group->find('all', array('conditions' => array('Group.id' => Sanitize::clean(intval($gid)))));
				$permissions = $this->Aacl->getAcoTree($uid, $group);
			}

			//set the view data
			$this->set(compact('groups', 'currentGroup', 'permissions'));
		// Save permissions
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

// ----------------------- ADMIN SETTINGS ----------------------- //

	public function admin_betaKeys() {
		if ($this->currentUser['User']['level'] > 0) {
			$this->redirect('/');
			exit;
		}
		$this->loadModel('BetaKey');

		$keys = $this->BetaKey->find('all');

		$this->set(compact('keys'));
	}

	public function admin_deletekey($id) {
		if (empty($id)) {
			$this->Session->setFlash(__('no_id_specified', true));
			$this->redirect($this->referer());
			exit;
		}

		$this->loadModel('BetaKey');
		if ($this->BetaKey->delete($id)) {
			$this->Session->setFlash(__('beta_key_deleted', true));
		}
		$this->redirect($this->referer());
		exit;
	}

	public function admin_generatekeys() {
		if (!empty($this->data)) {
			$this->loadModel('BetaKey');
			for ($i=0; $i<$this->data['BetaKey']['howmany']; $i++) {
				$key = sha1(Configure::read('Security.salt') . microtime());
				$this->BetaKey->create();
				$this->BetaKey->save(array('BetaKey' => array('key' => $key)));
			}
		}
		$this->redirect($this->referer());
		exit;
	}

	public function admin_inviteemail() {
		if (empty($this->data)) {
			$this->Session->setFlash(__('page_error', true));
			$this->redirect($this->referer());
		}

		$this->loadModel('BetaKey');
		$key = $this->BetaKey->useBetaKey($this->data['Email']['address']);

		$emailSettings = Configure::read('EmailInfo');

		$this->Email->from		= $emailSettings['from'];
		$this->Email->to		= '<' . $this->data['Email']['address'] . '>';
		$this->Email->subject	= __('site_name', true) . ' invitation.';
		$this->Email->sendAs	= 'both';
		$this->Email->template	= 'invite';
		$this->set('email', $this->data['Email']['address']);
		$this->set('key', $key);
		$this->Email->send();

		$this->Session->setFlash(__('beta_key_sent', true));
		$this->redirect($this->referer());
		exit;
	}

}