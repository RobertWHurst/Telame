<?php
class PagesController extends AppController {
	public $uses = array();
	public $helpers = array('Text', 'Time', 'Markdown');

	public function beforeFilter() {
		parent::beforeFilter();
		// Allows access to certain pages
		if ($this->RequestHandler->isRss()) {
			$this->Auth->allow('display', 'home', 'news');
		} else {
			$this->Auth->allow('display', 'home');
		}
	}

	public function beforeRender() {
		parent::beforeRender();

	}

	public function features() {
		//set the css and layout
		$this->layout = 'simple_header';

		$this->set('title_for_layout', __('site_name', true));
	}

	public function home() {
		//set the css and layout
		$this->layout = 'simple_header';

		$this->set('title_for_layout', __('site_name', true));
	}

	public function news($selectedFriendList = null, $uid = null, $hash = null) {
		$this->loadModel('Group');
		$this->loadModel('GroupsUser');
		$this->loadModel('WallPost');
		$uid = intval($uid);

		if ($this->RequestHandler->isRss()) {
			Configure::write('debug', 0);
			// this just checks that the hash is valid for the specified user
			$this->WallPost->User->recursive = -1;
			$user = $this->WallPost->User->find('first', array('conditions' => array('User.id' => $uid, 'User.rss_hash' => Sanitize::paranoid($hash))));
			$this->RequestHandler->setContent('rss');
			if (!$user) {
				return false;
			}
		} else {
			//set the layout
			$this->layout = 'tall_header_w_sidebar';
			$uid = $this->currentUser['User']['id'];
		}

		$friendLists = $this->Group->getFriendLists(array('uid' => $uid));

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

		$friends = $this->GroupsUser->getFriendIds($uid, $selectedFriendList);
		array_push($friends, $uid);

		if(!empty($friends)) {
			$wallPosts = $this->WallPost->getWallPosts(array(
//				'uid' => $friends,
				'aid' => $friends,
//				'baid' => $uid,
				'User' => true,
				'type' => array('post', 'media'),
				'limit' => 50
			));
		} else {
			$wallPosts = false;
		}

		$birthdays = $this->GroupsUser->getBirthdays($this->currentUser['User']['id']);

		$this->set(compact('birthdays', 'friendLists', 'wallPosts'));
	}


/**
 * Displays a view
 *
 * @param mixed What page to display
 * @access public
 */
	public function display() {
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
