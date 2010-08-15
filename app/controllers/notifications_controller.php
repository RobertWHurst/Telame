<?php
class NotificationsController extends AppController {

	var $helpers = array('Text', 'Time');

	function beforeFilter() {
		parent::beforeFilter();
		if ($this->RequestHandler->isRss()) {
			$this->Auth->allow('news');
		}
	}

	function markRead($id) {
		if (!$id) {
			$this->redirect('/');
			exit;
		}
		$this->read(null, $id);
		$this->set('new', false);
		$this->save();
	}

	function news($selectedFriendList = null, $uid = null, $hash = null) {
		$this->loadModel('Group');
		$this->loadModel('GroupsUser');
		$this->loadModel('WallPost');

		if( $this->RequestHandler->isRss() ) {
			Configure::write('debug', 0);
			$user = $this->WallPost->User->find('first', array('conditions' => array('User.id' => $uid, 'rss_hash' => $hash)));
			if (!$user) {
				return false;
			}
		} else {
			//set the layout
			$this->layout = 'tall_header_w_sidebar';
			$uid = $this->currentUser['User']['id'];
		}

		$friendLists = $this->Group->getFriendLists(0, 0, array('uid' => $uid));

		$default_friendLists = array(
			'all' => array('Group' => array('title' => 'Everyone', 'id' => 0)),
	//		'hidden' => array('FriendList' => array('name' => 'Hidden', 'id' => 'h'))
		);

		$friendLists = array_merge($default_friendLists, $friendLists);

		//add selected info
		foreach($friendLists as $key => $filter){
			if($filter['Group']['id'] == $selectedFriendList) {
				$friendLists[$key]['selected'] = true;
			} else {
				$friendLists[$key]['selected'] = false;
			}
		}

		$friends = $this->GroupsUser->getFriends(0, 0, array(
			'uid' => $uid,
			'gid' => $selectedFriendList
		));

		foreach($friends as $key => $friend)
			$friends[$key] = $friend['Friend']['id'];

		// add ourself to the list
		array_push($friends, $uid);

		$wallPosts = $this->WallPost->getWallPosts(0, 0, array('uid' => $friends, 'aid' => $friends, 'User' => true));
		$user = $this->currentUser;

		$this->set('title_for_layout', __('site_name', true) . ' | ' . __('news_title', true));

		$this->set(compact('user', 'wallPosts', 'friendLists'));
	}
}