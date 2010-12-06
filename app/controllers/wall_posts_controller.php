<?php
class WallPostsController extends AppController {

	public $components = array('RequestHandler', 'OauthConsumer');
	public $helpers = array('Html', 'Markdown', 'Time');

	public function beforeFilter() {
		parent::beforeFilter();
	}


	public function view_post($id){

		//set the layout
		$this->layout = 'new_tall_header_w_sidebar';

		//grab the matching wallpost
		$post = $this->WallPost->getWallPosts( $this->currentUser['User']['id'], array(
			'id' => $id
		));
	    $this->set( 'post', $post[0] );
	}


	public function add($reply_id = false) {
		// only loop around consumers that the user has checked
		foreach ($this->data['Oauth'] as $service => $state) {
			if ($state) {
				// we pass the current user array (which includes our oauth stuff) to the function, it will then find what we need and return
				$accessToken = $this->WallPost->User->Oauth->getAccessToken($service, $this->currentUser);
				// if we find an access token (we should)
				if ($accessToken) {
					// start the oauth process, we need to initiaze the consumer we're trying to use
					$this->OauthConsumer->begin($service);
					// get the class
					$consumer = $this->OauthConsumer->getConsumerClass();

					// post to that class, passing in the access token, the parent oauthconsumer, and the post
					$consumer->post($accessToken, $this->OauthConsumer, $this->data['WallPost']['post']);
				}
			}
		}

		$isAjax = $this->RequestHandler->isAjax();

		//make sure there is form data to process, if not there is not use in continuing
		if(empty($this->data)) {
			if ($isAjax) {
				echo 'false';
				exit;
			} else {
				$this->redirect(array('controller' => 'users', 'action' => 'profile', $this->currentUser['User']['Slug']));
				exit;
			}
		}

		//get the relpy id if any
		if(isset($this->data['WallPost']['reply_parent_id'])) {
			$reply_id = $this->data['WallPost']['reply_parent_id'];
		}

		//save the user id and the visitor id
		$wallOwnerId = $this->data['WallPost']['user_id'];

		// set vars we might need below
		$wallOwnerSlug = $this->WallPost->User->getSlugFromId($wallOwnerId);
		$visitorId = $this->currentUser['User']['id'];

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
					$visitorSlug = $this->WallPost->User->getSlugFromId($visitorId);
					$this->Session->setFlash(__('wall_post_error', true), 'default', array('class' => 'error'));
					$this->redirect(array('controller' => 'users', 'action' => 'profile', $visitorSlug));
					exit;
				}
			}
		}

		//save the user id and poster id
		$this->data['WallPost']['user_id'] = $wallOwnerId;
		$this->data['WallPost']['author_id'] = $visitorId;

		//commit the data to the db
		$this->WallPost->add($this->data, array('type' => 'post', 'class' => 'wall_post'));

		//get the new wall post id for ajax (if ajax)
		$new_post_id = $this->WallPost->id;

		//if the wall does not belong to the current user and it's not a reply
		if($wallOwnerId != $visitorId && $reply_id == null) {

			//create an action on the user's page
			$action['reply_parent_id'] = null;
			$action['WallPost']['user_id'] = $visitorId;
			$action['WallPost']['author_id'] = $wallOwnerId;
			$action['WallPost']['post'] = null;
			$this->WallPost->add($action, array('type' => 'post_action', 'class' => 'action'));
		}

		if($isAjax) {

			//load the view
			$wallPost = $this->WallPost->getWallPosts($this->currentUser['User']['id'], array('id' => $new_post_id, 'single' => true));

			//set the layout to none (this is ajax);
			$this->layout = false;

			//if this is a comment then load the comment element
			if($wallPost['WallPost']['reply_parent_id']) {
				$this->set(array('comment' => $wallPost, 'user' => $user, 'show_post_controls' => true, 'is_ajax' => true));
				$this->render('/elements/wall_post_comment');
			}
			//if this is a post then load the post element
			else {
				$this->set(array('post' => $wallPost, 'user' => $user, 'show_post_controls' => true, 'is_ajax' => true));
				$this->render('/elements/wall_post');
			}

		} else {
			//redirect the visitor to the wall they posted on
			$slug = $this->WallPost->User->getSlugFromId($wallOwnerId);
			$this->redirect(array('controller' => 'users', 'action' => 'profile', $slug));
			exit;
		}
	}

	public function delete($id = false) {
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
				$this->redirect($this->referer());
//				$this->redirect(array('controller' => 'users', 'action' => 'profile', $visitor_slug));
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
			//if everything checks out then delete the post and exit
			$this->WallPost->remove($id);
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
				$this->redirect($this->referer());
//				$this->redirect(array('controller' => 'users', 'action' => 'profile', $visitor_slug));
				exit;
			}
		}
	}

	public function dislike($id) {
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

	public function like($id) {
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

	public function more_posts( $uid = false, $offset = false ){



		//jettison the request if its not via ajax
		if( ! $this->RequestHandler->isAjax() ){
			//$this->redirect( $this->referer() );
			echo $uid;
		}

		if( ! $offset || ! $uid ){
			echo 'false';
			exit;
		}

		$wallPosts = $this->WallPost->getWallPosts( $this->currentUser['User']['id'], array( 'uid' => $uid, 'limit' => 10, 'offset' => $offset ) );

		//set the layout to none (this is ajax);
		$this->layout = false;

		$this->set( array( 'wallPosts' => $wallPosts ) );
	}

}
?>