<?php
class WallPostsController extends AppController {

	var $helpers = array('Markdown', 'Text', 'Time');

	function lists($id = false){
		echo 'test';
		exit;
	}

	function jx_lists($uid = false, $offset = false){
		if(!$offset || !$uid){
			echo 'false';
			exit;
		}

		$wallPosts = $this->WallPost->getWallPosts(10, $offset, array('uid' => $uid));

		//set the layout to none (this is ajax);
		$this->layout = false;

		$this->set('wallPosts', $wallPosts);
	}

	function add($reply_id = false) {
		$isAjax = $this->RequestHandler->isAjax();

		//make sure there is form data to proccess, if not there is not use in continuing
		if(empty($this->data)) {
			if ($isAjax) {
				echo 'false';
				exit;
			} else {
				$this->redirect(router::url(array('controller' => 'users', 'action' => 'profile', $visitor['User']['slug'])));
				exit;
			}
		}

		//save the user id and the visitor id
		$wallOwnerId = $this->data['WallPost']['user_id'];
		$visitorId = $this->currentUser['User']['id'];

		//findout if the current user is posting to there own wall. (will skip some un needed logic)
		if ($wallOwnerId != $visitorId) {

			//IF THE POSTER IS NOT THE THE WALL OWNER

			//if the poster is not friends with the user then return false
			if (!$this->WallPost->User->GroupsUser->isFriend($visitorId, $wallOwnerId)) {
				if($isAjax) {
					echo 'false';
					exit;
				} else {
					$this->Session->setFlash(__('wall_post_error', true));
					$this->redirect(array('controller' => 'users', 'action' => 'profile', $visitor['User']['slug']));
					exit;
				}
			}
		} else {
			//IF THE POSTER IS THE THE WALL OWNER

			//the visitor is the user
			$wallOwnerId = $visitorId;
		}

		if (!$reply_id) {
			$this->WallPost->set('reply_id', $reply_id);
		}
		//save the user id and poster id
		$this->WallPost->set('user_id', $wallOwnerId);
		$this->WallPost->set('author_id', $visitorId);

		//save the post type
		//TODO: this will change based on the content being posted.
		$this->WallPost->set('type', 1);

		//save the post content and time
		$this->WallPost->set('post', Sanitize::html($this->data['WallPost']['post'], array('remove' => true)));
		$this->WallPost->set('posted', date("Y-m-d H:i:s"));

		//commit the data to the db
		$this->WallPost->save();

		//TODO
		//we need to save a notification right here.

		if ($isAjax) {
			//get the new wall post id
			$new_post_id = $this->WallPost->id;
		
			//load the view
			$wallPosts = $this->WallPost->getWallPosts(1, 0, array('id' => $new_post_id));
		
			//set the layout to none (this is ajax);
			$this->layout = false;
		
			//send the new post to the view
			$this->set('wallPosts', $wallPosts);
		} else {
			//redirect the visitor to the wall they posted on
			$slug = $this->WallPost->User->getSlugFromId($wallOwnerId);
			$this->redirect(router::url(array('controller' => 'users', 'action' => 'profile', $slug)));
			exit;
		}
	}

	function delete($id = false) {
		$isAjax = $this->RequestHandler->isAjax();

		//get the visitor's data
		$this->WallPost->User->recursive = -1;
		$visitor = $this->WallPost->User->findById($this->currentUser['User']['id']);

		//if the wall id is missing
		if(!$id) {
			if ($isAjax) {
				echo 'false';
				exit;
			} else {
				$this->Session->setFlash(__('wall_post_delete_error', true));
				$this->redirect(router::url(array('controller' => 'users', 'action' => 'profile', $visitor['User']['slug'])));
				exit;
			}
		}

		//check to make sure the user is deleting a wall psot they actually own
		$conditions = array(
			'wall_posts.id' => $id,
			'wall_posts.user_id' => $visitor['User']['id']
		);

		//if we don't find one then deny them the action
		if($this->WallPost->find('count', array('conditions' => $conditions)) < 1){

			//set the flash message and redirect them, the metaling sods! :<
			$this->Session->setFlash(__('wall_post_bad_hacker', true));
			$this->redirect(router::url(array('controller' => 'users', 'action' => 'profile', $visitor['User']['slug'])));
			exit;
		}

		//if everything checks out then delete the post and exit
		$this->WallPost->delete($id);
		$this->Session->setFlash(__('wall_post_delete', true));
		$this->redirect(router::url(array('controller' => 'users', 'action' => 'profile', $visitor['User']['slug'])));
		exit;
	}

	function jx_delete($id = false) {

		//get the visitor's data
		$visitor = $this->WallPost->User->findById($this->currentUser['User']['id']);

		//if the wall id is missing
		if(!$id)
			echo 'false';

		//check to make sure the user is deleting a wall post they actually own or that they are the author
		$condition_set_1 = array(
			'wall_posts.id' => $id,
			'wall_posts.author_id' => $this->currentUser['User']['id']
		);
		$condition_set_2 = array(
			'wall_posts.id' => $id,
			'wall_posts.user_id' => $this->currentUser['User']['id']
		);

		//if we don't count one and they are not the authors then deny them the action
		if(
			$this->WallPost->find('count', array(
				'conditions' => $condition_set_1
			)) < 1 &&

			$this->WallPost->find('count', array(
				'conditions' => $condition_set_2
			)) < 1
		)
			echo 'false';
		else{
			$this->WallPost->delete($id);
			echo 'true';
		}
		exit;
	}
}
?>