<?php
class Message extends AppModel {
	var $belongsTo = array(
		'User' => array(
			'className' => 'User',
			'foreignKey' => 'user_id'
		),
		'Author' => array(
			'className' => 'User',		
			'foreignKey' => 'author_id'
		)
	);

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
		
		$this->recursive = 2;
		$this->Behaviors->attach('Containable');
		
		return $this->find('all', array(
				'conditions' => array(
					'user_id' => $uid,
					'deleted' => false
				),
				'contain' => array(
					'User' => array(
						'Profile'
					),
					'Author' => array(
						'Profile'
					)
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