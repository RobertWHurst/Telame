<?php
// this is a helper controller, it doesn't have a 'friend' model, it only uses other models
class FriendsController extends AppController {

	var $uses = array();

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
			$friendLists = $this->User->Group->getFriendLists(0, 0, array(
				'uid' => $this->currentUser['User']['id'],
				'type' => 'list',
				));
			$slug = $this->User->getSlugFromId($fid);
			$friend = $this->User->getProfile($slug);
			$this->set(compact('friend', 'friendLists'));
		}
	}

	function friendList($slug = false) {
		$this->loadModel('User');
		//set the layout
		$this->layout = 'profile';

		if (!$slug) {
			$user = $this->currentUser;
		} else {
			$user = $this->User->getProfile($slug);
		}

		if($this->Aacl->checkPermissions($user['User']['id'], $this->currentUser['User']['id'], 'friends')) {
			$friends = $this->User->GroupsUser->getFriends(0, 0, array('uid' => $user['User']['id']));
		} else {
			$this->Session->setFlash(__('not_allowed_friends', true), 'default', array('class' => 'warning'));
			$this->redirect($this->referer());
		}
		$this->set(compact('friends', 'user'));
	}

}

?>