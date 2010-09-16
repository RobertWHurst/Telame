<?php
class GroupsUsersController extends AppController {

	var $helpers = array('Paginator');

	/**
	 * \brief addFriend optionally takes a user id and adds them to your friends list.
	 *
	 * If a user id is specified, and post data is empty, a page is loaded asking which list you want to add the user to.  
	 * Once there is post data there should be the frind ID, and the list ID, then add them to the DB
	 */
	function addFriend($fid = null, $confirm = null, $cid = null) {
		$this->loadModel('User');
		$this->layout = 'tall_header_w_sidebar';

		if (!$this->GroupsUser->isFriend($this->currentUser['User']['id'], $fid)) {
			if ($this->GroupsUser->requestSent($this->currentUser['User']['id'], $fid)) {
				$this->Session->setFlash(__('friend_request_already_sent', true));
				$this->redirect($this->referer());
			}
		}

		// Save data, we will check for a confirm vs an initial add inside
		if (!empty($this->data) && !is_null($fid)) {
			$this->data['GroupsUser']['user_id'] = $this->currentUser['User']['id'];
			$this->User->GroupsUser->create();
			if ($this->User->GroupsUser->save($this->data)) {
				// only make a notification if this isn't a confirm
				$this->loadModel('Notification');
				if (!$this->data['GroupsUser']['confirm']) {

					$this->Notification->addNotification(
						$fid,
						'groups_user',
						__('friend_request', true),
//						$this->data['GroupsUser']
						'You have a friend request',
						$this->User->GroupsUser->id
					);
					$uid = $fid;
				} else {
					$uid = $this->currentUser['User']['id'];
					if ($this->Notification->markRead($this->data['GroupsUser']['cid'], $this->currentUser['User']['id'])) {
						$this->Session->setFlash(__('bad_hacker', true));
					}
				}
				$this->Session->setFlash(__('friend_added_successfully', true));
			}
			$slug = $this->User->getSlugFromId($uid);
			$this->redirect(array('controller' => 'users', 'action' => 'profile', $slug));
		// Display the adding page
		} else {
			$friendLists = $this->User->Group->getFriendLists(array(
				'uid' => $this->currentUser['User']['id'],
				'type' => 'list',
				));
				
			$slug = $this->User->getSlugFromId($fid);
			$friend = $this->User->getProfile($slug);
			$this->set(compact('confirm', 'cid', 'friend', 'friendLists'));
		}
	}

	function friendList($slug = false) {
		//set the layout
		$this->layout = 'profile';

		if (!$slug) {
			$user = $this->currentUser;
		} else {
			$user = $this->GroupsUser->User->getProfile($slug);
		}

		if($this->Aacl->checkPermissions($user['User']['id'], $this->currentUser['User']['id'], 'friends')) {
			$this->paginate = array(
				'conditions' => array(
					'user_id' => $this->currentUser['User']['id'],
				),
				'contain' => array(
					'Friend.Profile',
				),
				'limit' => 1,
				'order' => array(
					'Friend.first_name',
					'Friend.last_name',
				),
			);
			$friends = $this->paginate('GroupsUser');
		} else {
			$this->Session->setFlash(__('not_allowed_friends', true), 'default', array('class' => 'warning'));
			$this->redirect($this->referer());
		}
		$this->set(compact('friends', 'user'));
	}

}

?>