<?php
class WallPostsController extends AppController {

	//Controller config
	var $name = 'WallPosts';


	function add() {
		if (!empty($this->data)) {
			$this->WallPost->set('user_id', $this->data['WallPost']['user_id']);
			$this->WallPost->set('post', $this->data['WallPost']['post']);
			$this->WallPost->set('poster_id', Configure::read('UID'));
			
			$this->WallPost->save();
		}
		$user = $this->WallPost->User->findById($this->data['WallPost']['user_id']);
		$this->redirect('/p/' . $user['User']['slug']);
		exit();
	}
	
	function delete($id) {
		if(!$id) {
			$this->setFlash(__('Invalid Wall Post', true));
			$this->redirect('/');
			exit();
		}
		$this->WallPost->delete($id);
		$this->redirect('/p');
		exit();
	}
}
?>