<?php
class WallPostsController extends AppController {

	//Controller config
	var $name = 'WallPosts';

	function add() {
		//make sure there is form data to proccess, if not there is not use in continuing
		if(empty($this->data)) {
			$this->redirect(router::url(array('controller' => 'users', 'action' => 'profile', $visitor['User']['slug'])));
			exit();
		}

		//save the user id and the visitor id
		$user_id = $this->data['WallPost']['user_id'];
		$visitor_id = Configure::read('UID');

		//get the visiting user's data
		$visitor = $this->WallPost->User->findById($visitor_id);

		//findout if the current user is posting to there own wall. (will skip some un needed logic)
		if ($user_id != $visitor_id) {

			//IF THE POSTER IS NOT THE THE WALL OWNER

			//get the user's id that owns the wall
			$user = $this->WallPost->User->findById($user_id);

			//find out if the visitor is a friend
			$conditions = array(
				//find links that belong to the user owning the wall
				'parent_user_id' => $user['User']['id'],

				//and that link to the visitor
				'child_user_id' => $visitor['User']['id']
			);

			//if the poster is not friends with the user then return false
			if ($this->WallPost->User->Friend->find('count', array('conditions' => $conditions)) < 1) {
				$this->Session->setFlash(__('No, I can\'t let you do that. Only people that are friends can post on the wall', true));
				$this->redirect(router::url(array('controller' => 'users', 'action' => 'profile', $visitor['User']['slug'])));
				exit();
			}
		} else {
			//IF THE POSTER IS THE THE WALL OWNER

			//the visitor is the user
			$user = $visitor;
		}

		//save the user id and poster id
		$this->WallPost->set('user_id', $user['User']['id']);
		$this->WallPost->set('post_author_id', $visitor['User']['id']);

		//save the post content and time
		$this->WallPost->set('post', $this->data['WallPost']['post']);
		$this->WallPost->set('posted', date("Y-m-d H:i:s"));

		//commit the data to the db
		$this->WallPost->save();

		//TODO
		//we need to save a notification right here.

		//redirect the visitor to the wall they posted on
		$this->redirect(router::url(array('controller' => 'users', 'action' => 'profile', $user['User']['slug'])));
		exit();
	}

	function delete($id = false) {

		//get the visitor's data
		$visitor = $this->WallPost->User->findById(Configure::read('UID'));

		//if the wall id is missing
		if(!$id) {
			$this->Session->setFlash(__('Uhh... Sorry We\'ve Looked everywhere but we can\'t find that post. Try again if you can find it.', true));
			$this->redirect(router::url(array('controller' => 'users', 'action' => 'profile', $visitor['User']['slug'])));
			exit();
		}

		//check to make sure the user is deleting a wall psot they actually own
		$conditions = array(
			'wall_posts.id' => $id,
			'wall_posts.user_id' => $visitor['User']['id']
		);

		//if we don't find one then deny them the action
		if($this->WallPost->find('count', array('conditions' => $conditions)) < 1){

			//set the flash message and redirect them, the metaling sods! :<
			$this->Session->setFlash(__('Bit of a hacker are we? well sorry you can\'t delete that post. It\'s not on your wall!', true));
			$this->redirect(router::url(array('controller' => 'users', 'action' => 'profile', $visitor['User']['slug'])));
			exit();
		}

		//if everything checks out then delete the post and exit
		$this->WallPost->delete($id);
		$this->Session->setFlash(__('The wall post is toast!', true));
		$this->redirect(router::url(array('controller' => 'users', 'action' => 'profile', $visitor['User']['slug'])));
		exit();
	}
}
?>