<?php
class notificationsController extends AppController {
	var $name = 'notifications';
	var $helpers = array('time');
	var $uses = array(
		'Notification',
		'Friend',
		'WallPost'
	);

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
			'main_sidebar',
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

	function news() {
		$this->Includer->add('css', array(
			'notifications/news_feed'
		));
		$this->Includer->add('script', array(
			//scripts
		));
		$this->Friend = new Friend();
		$friends = $this->Friend->getFriendList(Configure::read('UID'));
		
		$this->WallPost = new WallPost();
		
		$wallPosts = $this->WallPost->getWallPosts(null, null, null, $friends);
		$user = $this->currentUser;
		
		$this->set(compact('user', 'wallPosts'));
	}
}