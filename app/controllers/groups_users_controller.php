<?php
class GroupsUsersController extends AppController {

	var $helpers = array('Paginator');

	/**
	 * \brief addFriend optionally takes a user id and adds them to your friends list.
	 *
	 * If a user id is specified, and post data is empty, a page is loaded asking which list you want to add the user to.  
	 * Once there is post data there should be the frind ID, and the list ID, then add them to the DB
	 */
	function addFriend($fid = null) {
		$this->loadModel('User');
		$this->layout = 'tall_header_w_sidebar';
		if (!empty($this->data) && !is_null($fid)) {
			$this->data['GroupsUser']['user_id'] = $this->currentUser['User']['id'];
			if ($this->User->GroupsUser->save($this->data)) {
				$this->loadModel('Notification');

				$this->Notification->addNotification(
					$fid,
					'friend',
					__('friend_request', true),
//					$this->data['GroupsUser']
					'You have a friend request'

				);
			}
			$slug = $this->User->getSlugFromId($fid);
			$this->redirect(array('controller' => 'users', 'action' => 'profile', $slug));
		} else {
			$friendLists = $this->User->Group->getFriendLists(array(
				'uid' => $this->currentUser['User']['id'],
				'type' => 'list',
				));
			$slug = $this->User->getSlugFromId($fid);
			$friend = $this->User->getProfile($slug);
			$this->set(compact('friend', 'friendLists'));
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