<?php
class WallPostLike extends AppModel {

	var $belongsTo = array('WallPost');

	function doLike($wpid, $uid, $like) {
		$wpid = intval($wpid);
		$uid = intval($uid);
		$wp = $this->find('first', array('conditions' => array('WallPostLike.user_id' => $uid, 'wall_post_id' => $wpid)));
		if ($wp) {
			$this->id = $wp['WallPostLike']['id'];
			$this->saveField('like', ($like ? true : false));
		} else {
			$this->create();
			$data = array('WallPostLike.user_id' => $uid, 'WallPostLike.wall_post_id' => $wpid);
			$this->save($data);
		}
		$likeCount = $this->find('count', array('conditions' => array('WallPostLike.wall_post_id' => $wpid, 'WallPostLike.like' => true)));
		$dislikeCount = $this->find('count', array('conditions' => array('WallPostLike.wall_post_id' => $wpid, 'WallPostLike.like' => false)));

		$this->WallPost->id = $wp['WallPost']['id'];
		$this->WallPost->saveField('like', $likeCount);
		$this->WallPost->saveField('dislike', $dislikeCount);
		
		return array('likeCount' => $likeCount, 'dislikeCount' => $dislikeCount);
	}
}

?>