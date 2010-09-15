<?php
class WallPostsController extends AppController {

	var $components = array('RequestHandler');
	var $helpers = array('Markdown', 'Time');

	function beforeFilter() {
		parent::beforeFilter();
	}

	function lists($id = false){
		echo 'test';
		exit;
	}

	function jx_lists($uid = false, $offset = false){
		if(!$offset || !$uid){
			echo 'false';
			exit;
		}

		$wallPosts = $this->WallPost->getWallPosts(array('uid' => $uid, 'limit' => 10, 'offset' => $offset));

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
				$this->redirect(array('controller' => 'users', 'action' => 'profile', $currentUser['User']['Slug']));
				exit;
			}
		}

		//get the relpy id if any
		if(isset($this->data['WallPost']['reply_parent_id']))
			$reply_id = $this->data['WallPost']['reply_parent_id'];

		//save the user id and the visitor id
		$wallOwnerId = $this->data['WallPost']['user_id'];

		$wallOwnerSlug = $this->WallPost->User->getSlugFromId($wallOwnerId);
		$visitorId = $this->currentUser['User']['id'];
		$visitorSlug = $this->WallPost->User->getSlugFromId($visitorId);

		//get the user object so it can be used by the wall post element
		$user = $this->WallPost->User->getProfile($wallOwnerSlug);

		//findout if the current user is posting to there own wall. (will skip some un needed logic)
		if ($wallOwnerId != $visitorId) {

			//IF THE POSTER IS NOT THE THE WALL OWNER

			//if the poster is not friends with the user then return false
			if (!$this->WallPost->User->GroupsUser->isFriend($visitorId, $wallOwnerId)) {
				if($isAjax) {
					echo 'false';
					exit;
				} else {
					$this->Session->setFlash(__('wall_post_error', true), 'default', array('class' => 'error'));
					$this->redirect(array('controller' => 'users', 'action' => 'profile', $visitorSlug));
					exit;
				}
			}
		} else {
			//IF THE POSTER IS THE THE WALL OWNER

			//the visitor is the user
			$wallOwnerId = $visitorId;
		}

		$this->WallPost->create();

		if($reply_id){
			$this->WallPost->set('reply_parent_id', $reply_id);
		}
		//save the user id and poster id
		$this->WallPost->set('user_id', $wallOwnerId);
		$this->WallPost->set('author_id', $visitorId);

		//save the post type
		//TODO: this will change based on the content being posted.
		$this->WallPost->set('type', 'post');

		//save the post content and time
		$this->WallPost->set('post', strip_tags($this->data['WallPost']['post']));
		$this->WallPost->set('posted', date("Y-m-d H:i:s"));

		//commit the data to the db
		$this->WallPost->save();

		//TODO
		//we need to save a notification right here.

		// FIXME
		if($isAjax){
			//get the new wall post id
			$new_post_id = $this->WallPost->id;

			//load the view
			$wallPost = $this->WallPost->getWallPosts(array('id' => $new_post_id, 'single' => true));
			
			//set the layout to none (this is ajax);
			$this->layout = false;
			
			//if this is a comment then load the comment element
			if($wallPost['WallPost']['reply_parent_id']) {
				$this->set(array('comment' => $wallPost, 'user' => $user, 'show_post_controls' => true));
				$this->render('/elements/wall_post_comment');
			}
			//if this is a post then load the post element
			else{
				$this->set(array('post' => $wallPost, 'user' => $user, 'show_post_controls' => true));
				$this->render('/elements/wall_post');
			}

		}
		else{
			//redirect the visitor to the wall they posted on
			$slug = $this->WallPost->User->getSlugFromId($wallOwnerId);
			$this->redirect(array('controller' => 'users', 'action' => 'profile', $slug));
			exit;
		}
	}

	function delete($id = false) {
		$isAjax = $this->RequestHandler->isAjax();
		$uid = $this->currentUser['User']['id'];

		//get the visitor's data
		$this->WallPost->User->recursive = -1;
		$visitor_slug = $this->WallPost->User->getSlugFromId($uid);

		//if the wall id is missing
		if(!$id) {
			if ($isAjax) {
				echo 'false';
				exit;
			} else {
				$this->Session->setFlash(__('wall_post_delete_error', true), 'default', array('class' => 'error'));
				$this->redirect(array('controller' => 'users', 'action' => 'profile', $visitor_slug));
				exit;
			}
		}

		//check to make sure the user is deleting a wall post they actually own or that they are the author of
		$wallPost = $this->WallPost->find('first', array(
			'conditions' => array(
				'WallPost.id' => $id,
				'OR' => array(
					'WallPost.author_id' => $uid,
					'WallPost.user_id' => $uid
				)
			)
		));
		// will return false if we don't find a post matching the conditions
		if ($wallPost) {
			// delete all replies first
			$this->WallPost->deleteAll(array('WallPost.reply_parent_id' => $wallPost['WallPost']['id']));

			//if everything checks out then delete the post and exit
			$this->WallPost->delete($id);
			if($isAjax){
				echo 'true';
				exit;
			} else {
				$this->Session->setFlash(__('wall_post_delete', true), 'default', array('class' => 'info'));
				$this->redirect(array('controller' => 'users', 'action' => 'profile', $visitor_slug));
				exit;
			}
		} else {
			if ($isAjax) {
				echo 'false';
				exit;
			} else {
				//set the flash message and redirect them, the metaling sods! :<
				$this->Session->setFlash(__('wall_post_bad_hacker', true), 'default', array('class' => 'warning'));
				$this->redirect(array('controller' => 'users', 'action' => 'profile', $visitor_slug));
				exit;
			}
		}
	}

	function dislike($id) {
		$isAjax = $this->RequestHandler->isAjax();

		$this->WallPost->WallPostLike->doLike($id, $this->currentUser['User']['id'], false);

		//if not an ajax call redirect from the referer
    	if($isAjax){
    		echo 'true';
    	}
    	else
    		$this->redirect($this->referer());
    	exit;
	}

	function like($id) {
		$isAjax = $this->RequestHandler->isAjax();

		$this->WallPost->WallPostLike->doLike($id, $this->currentUser['User']['id'], true);

		//if not an ajax call redirect from the referer
    	if($isAjax){
    		echo 'true';
    	}
    	else
    		$this->redirect($this->referer());
    	exit;
	}
}
?>