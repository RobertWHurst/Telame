<?php
class notificationsController extends AppController {
	var $name = 'notifications';
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
			'users/main_sidebar'
		));
		$this->Includer->add('script', array(
			'jquery',
			'users/main_sidebar',
		));
	}

	function news() {
		$this->Friend = new Friend();
		$friends = $this->Friend->getFriendList(Configure::read('UID'));
		
		$this->WallPost = new WallPost();
		
		$wallPosts = $this->WallPost->getWallPosts(null, null, null, $friends);
		$user = $this->currentUser;
		
		$this->set(compact('user', 'wallPosts'));
	}
}