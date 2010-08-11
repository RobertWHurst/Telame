<?php
class Message extends AppModel {
	var $belongsTo = array('User');

	function getMessage($uid, $mid){
		return $this->find('first', array(
				'conditions' => array(
					'user_id' => $uid,
					'id' => $mid
				)
			)
		);
	}

	function getRecieved($uid){
		return $this->find('all', array(
				'conditions' => array(
					'user_id' => $uid,
					'deleted' => false
				)
			)
		);
	}
	
	function getDeleted($uid){
		return $this->find('all', array(
				'conditions' => array(
					'user_id' => $uid,
					'deleted' => true
				)
			)
		);
	}
	
	function getSent($uid){
		return $this->find('all', array(
				'conditions' => array(
					'author_id' => $uid,
					'deleted' => false
				)
			)
		);
	}
}