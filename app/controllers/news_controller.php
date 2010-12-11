<?php
class NewsController extends AppController {

	public $components = array('WallPosts');
	public $helpers = array('Text', 'Time', 'Markdown');
	public $uses = array();

	public function beforeFilter() {
		parent::beforeFilter();
		if ($this->RequestHandler->isRss()) {
			$this->Auth->allow('news');
		}
	}

	public function beforeRender(){
		parent::beforeRender();

		//set the layout
		$this->layout = 'new_tall_header_w_sidebar';
	}

	public function news($selectedFriendList = null, $uid = null, $hash = null) {
		$this->WallPosts->getWallPosts(array(
			'all_friends' => true,
			'selectedFriendList' => $selectedFriendList,
			'rss_uid' => $uid,
			'rss_hash' => $hash,
		));

		$allowedGroups = $this->Aacl->getAllowedGroups('WallPost');
		$groups = $this->User->Group->getFriendLists(array('uid' => $this->currentUser['User']['id'], 'type' => 'list'));

		// find any friends who have a birthday today
		$this->loadModel('GroupsUser');
		$birthdays = $this->GroupsUser->getBirthdays($this->currentUser['User']['id']);

		$this->set(compact('allowedGroups', 'birthdays', 'groups'));
	}

	public function more_news($selectedFriendList = null, $uid = false, $offset = false) {

		//jettison the request if its not via ajax
		if (!$this->RequestHandler->isAjax()) {
			$this->redirect($this->referer());
		}

		if (!$offset || !$uid) {
			echo 'false';
			exit;
		}

		$friends = $this->GroupsUser->getFriendIds($uid, $selectedFriendList);
		array_push($friends, $uid);

		if(!empty($friends)) {
			$wallPosts = $this->WallPost->getWallPosts( $this->currentUser['User']['id'], array(
				'aid' => $friends,
				'User' => true,
				'type' => array('post', 'media'),
			));
		} else {
			$wallPosts = false;
		}

		//set the layout to none (this is ajax);
		$this->layout = false;

		$this->set( array( 'wallPosts' => $wallPosts, 'user' => $this->WallPost->User->getProfile( $uid ) ) );
	}


}