<?php
class BreweryVote extends BreweryAppModel {
	var $name = 'BreweryVote';
	//The Associations below have been created with all possible keys, those that are not needed can be removed

	var $belongsTo = array(
		'User' => array(
			'className' => 'User',
			'foreignKey' => 'user_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
		'BreweryProject' => array(
			'className' => 'Brewery.BreweryProject',
		)
	);

	// projectId, UserId, like = true/false
	public function doVote($pid, $uid, $like) {
		$pid = intval($pid);
		$uid = intval($uid);
		// check if the user has alread voted by searching for their user id on the wall post they're trying to vote for
		$wp = $this->find('first', array('conditions' => array('BreweryVote.user_id' => $uid, 'BreweryVote.brewery_project_id' => $pid)));

		// they've voted, change their option
		if ($wp && $wp['BreweryVote']['like'] != $like) {
			$this->id = $wp['BreweryVote']['id'];
			$this->saveField('like', ($like ? true : false));
		} // they voted, but want to remove it
		elseif ($wp && $wp['BreweryVote']['like'] == $like) {
			$this->delete($wp['BreweryVote']['id']);
		} // new vote 
		else {
			$this->create();
			$data = array('BreweryVote' => array('user_id' => $uid, 'brewery_project_id' => $pid, 'like' => $like));
			$this->save($data);
		}
		// count the likes and dislikes
		$likeCount = $this->find('count', array('conditions' => array('BreweryVote.brewery_project_id' => $pid, 'BreweryVote.like' => true)));
		$dislikeCount = $this->find('count', array('conditions' => array('BreweryVote.brewery_project_id' => $pid, 'BreweryVote.like' => false)));

		// set it in the wall posts table
		$this->BreweryProject->id = $pid;
		$this->BreweryProject->saveField('likes', $likeCount);
		$this->BreweryProject->saveField('dislikes', $dislikeCount);
		
		return array('likeCount' => $likeCount, 'dislikeCount' => $dislikeCount);
	}
}