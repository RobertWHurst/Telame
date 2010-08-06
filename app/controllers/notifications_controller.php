<?php
class NotificationsController extends AppController {
	var $name = 'notifications';
	var $helpers = array('time');
	var $uses = array();

	function beforeFilter(){
		parent::beforeFilter();
		//add css and js that is common to all the actions in this controller
		$this->Includer->add('css', array(
			'base',
			'tall_header',
			'main_sidebar'
		));
		$this->Includer->add('script', array(
			'jquery',
			'base',
			'main_sidebar'
		));
	}

	//Before the render of all views in this controller
	function beforeRender() {
		//run the before render in the app controller
		parent::beforeRender();
		//set the css and script for the view
		$this->set('css_for_layout', $this->Includer->css());
		$this->set('script_for_layout', $this->Includer->script());
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

	function news($selectedFriendList = null) {
				
		App::Import('Model', 'Friend');
		App::Import('Model', 'FriendList');
		App::Import('Model', 'WallPost');
		
		$this->Friend = new Friend();
		$this->FriendList = new FriendList();
		$this->WallPost = new WallPost();
				
		$friendLists = $this->FriendList->getFriendLists(0, 0, array('uid' => $this->currentUser['User']['id']));
		
		$default_friendLists = array(
			'all' => array('FriendList' => array('name' => 'Everyone', 'id' => null)),
			//'hidden' => array('FriendList' => array('name' => 'Hidden', 'id' => 'h'))
		);
		
		$friendLists = array_merge($default_friendLists, $friendLists);
		
		//add selected info
		foreach($friendLists as $key => $filter){
			if($filter['FriendList']['id'] == $selectedFriendList)
				$friendLists[$key]['selected'] = true;
			else
				$friendLists[$key]['selected'] = false;				
		}
		
		$this->Includer->add('css', array(
			'notifications/news_feed',
			'notifications/news_sidebar'
		));
		$this->Includer->add('script', array(
			//scripts
		));
		
		$friends = $this->Friend->getFriends(0, 0, array(
			'uid' => $this->currentUser['User']['id'],
			'list' => true,
			'friendsList' => $selectedFriendList
		));
		// add ourself to the list
		array_push($friends, $this->currentUser['User']['id']);
		
		$wallPosts = $this->WallPost->getWallPosts(0, 0, array('uid' => $friends, 'aid' => $friends, 'User' => true));
		$user = $this->currentUser;

		$this->set('title_for_layout', __('site_name', true) . ' | ' . __('news_title', true));
		
		$this->set(compact('user', 'wallPosts', 'friendLists'));
	}
}