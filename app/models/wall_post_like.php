<?php
class WallPostLike extends AppModel {

	public $belongsTo = array('User', 'WallPost');

	public function doLike($wpid, $uid, $like) {
		$wpid = intval($wpid);
		$uid = intval($uid);
		// check if the user has alread voted by searching for their user id on the wall post they're trying to vote for
		$wp = $this->find('first', array('conditions' => array('WallPostLike.user_id' => $uid, 'wall_post_id' => $wpid)));

		// they've voted, change their option
		if ($wp && $wp['WallPostLike']['like'] != $like) {
			$this->id = $wp['WallPostLike']['id'];
			$this->saveField('like', ($like ? true : false));
		} // they voted, but want to remove it
		elseif ($wp && $wp['WallPostLike']['like'] == $like) {
			$this->delete($wp['WallPostLike']['id']);
		} // new vote 
		else {
			$this->create();
			$data = array('WallPostLike' => array('user_id' => $uid, 'wall_post_id' => $wpid, 'like' => $like));
			$this->save($data);
		}
		// count the likes and dislikes
		$likeCount = $this->find('count', array('conditions' => array('WallPostLike.wall_post_id' => $wpid, 'WallPostLike.like' => true)));
		$dislikeCount = $this->find('count', array('conditions' => array('WallPostLike.wall_post_id' => $wpid, 'WallPostLike.like' => false)));

		// set it in the wall posts table
		$this->WallPost->id = $wpid;
		$this->WallPost->saveField('like', $likeCount);
		$this->WallPost->saveField('dislike', $dislikeCount);
		
		return array('likeCount' => $likeCount, 'dislikeCount' => $dislikeCount);
	}
}

?>