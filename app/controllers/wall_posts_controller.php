<?php
class WallPostsController extends AppController {

	//Controller config
	var $name = 'WallPosts';


	function add() {
		if (!empty($this->data)) {
			$this->WallPost->set('user_id', Configure::read('UID'));
			$this->WallPost->set('post', $this->data['WallPost']['post']);
			$this->WallPost->save();
		}
		$this->redirect('/p/');
		exit();
	}
}
?>