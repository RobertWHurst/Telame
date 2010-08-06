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

	function news($selectedfilter = null) {
		
		//FILTERS
		//TODO: this should pull from user config
		$user_filters = array(
			'list1' => array('name' => 'List 1', 'uri' => '_'),
			'list2' => array('name' => 'List 2', 'uri' => '_'),
			'list3' => array('name' => 'List 3', 'uri' => '_')
		);
		
		$default_filters = array(
			'all' => array('name' => 'Everyone', 'uri' => null),
			'hidden' => array('name' => 'Hidden', 'uri' => 'h')
		);
		
		$filters = array_merge($default_filters, $user_filters);
		
		//add selected info
		foreach($filters as $key => $filter){
			if($filter['uri'] == $selectedfilter)
				$filters[$key]['selected'] = true;
			else
				$filters[$key]['selected'] = false;				
		}
		
		App::Import('Model', 'Friend');
		App::Import('Model', 'WallPost');
		
		$this->Friend = new Friend();
		$this->WallPost = new WallPost();

		$this->Includer->add('css', array(
			'notifications/news_feed',
			'notifications/news_sidebar'
		));
		$this->Includer->add('script', array(
			//scripts
		));
		$this->Friend = new Friend();
		$friends = $this->Friend->getFriendList(Configure::read('UID'));
		// add ourself to the list
		array_push($friends, Configure::read('UID'));
		
		$this->WallPost = new WallPost();
		
		$wallPosts = $this->WallPost->getWallPosts(0, 0, array('uid' => $friends, 'aid' => $friends, 'User' => true));
		$user = $this->currentUser;
		
		$this->set(compact('user', 'wallPosts', 'filters'));
	}
}