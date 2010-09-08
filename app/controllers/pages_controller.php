<?php
class PagesController extends AppController {
	var $uses = array();
	var $helpers = array('Text', 'Time', 'Markdown');

	function beforeFilter() {
		parent::beforeFilter();
		// Allows access to certain pages
		if ($this->RequestHandler->isRss()) {
			$this->Auth->allow('display', 'home', 'news');
		} else {
			$this->Auth->allow('display', 'home');
		}
	}

	function beforeRender() {
		parent::beforeRender();
		
	}

	function features() {
		//set the css and layout
		$this->layout = 'simple_header';
		
		$this->set('title_for_layout', __('site_name', true));
	}

	function home() {
		//set the css and layout
		$this->layout = 'simple_header';
		
		$this->set('title_for_layout', __('site_name', true));
	}

	function news($selectedFriendList = null, $uid = null, $hash = null) {
		$this->loadModel('Group');
		$this->loadModel('GroupsUser');
		$this->loadModel('WallPost');

		if( $this->RequestHandler->isRss() ) {
			Configure::write('debug', 0);
			// this just checks that the hash is valid for the specified user
			$this->WallPost->User->recursive = -1;
			$user = $this->WallPost->User->find('first', array('conditions' => array('User.id' => intval($uid), 'User.rss_hash' => $hash)));

			if (!$user) {
				return false;
			}
		} else {
			//set the layout
			$this->layout = 'tall_header_w_sidebar';
			$uid = $this->currentUser['User']['id'];
		}

		$friendLists = $this->Group->getFriendLists(0, 0, array('uid' => $uid));

		$psudeoLists = array(
			'all' => array('Group' => array('title' => 'Everyone', 'id' => 0))
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

		$friends = $this->GroupsUser->getFriends(array(
			'uid' => $uid,
			'gid' => $selectedFriendList
		));

		foreach($friends as $key => $friend)
			$friends[$key] = $friend['Friend']['id'];

		// add ourself to the list
		array_push($friends, $uid);

		$wallPosts = $this->WallPost->getWallPosts(20, 0, array('uid' => $friends, 'aid' => $friends, 'User' => true));
		$user = $this->currentUser;

		$this->set('title_for_layout', __('site_name', true) . ' | ' . __('news_title', true));

		$this->set(compact('user', 'wallPosts', 'friendLists'));
	}


/**
 * Displays a view
 *
 * @param mixed What page to display
 * @access public
 */
	function display() {
		$path = func_get_args();

		$count = count($path);
		if (!$count) {
			$this->redirect('/');
		}
		$page = $subpage = $title_for_layout = null;

		if (!empty($path[0])) {
			$page = $path[0];
		}
		if (!empty($path[1])) {
			$subpage = $path[1];
		}
		if (!empty($path[$count - 1])) {
			$title_for_layout = Inflector::humanize($path[$count - 1]);
		}
		// add this snippet before the last line
		if (method_exists($this, $page)) {
			$this->$page();
		}
 		$this->set(compact('page', 'subpage', 'title_for_layout'));
		$this->render(implode('/', $path));
	}
}
