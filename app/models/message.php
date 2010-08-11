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
	var $hasMany = array(
		'ChildMessage' => array(		
			'className' => 'Message',		
			'foreignKey' => 'parent_id',
			'dependent' => true
		)
	);

	function getMessageThread($uid, $mid){
		
		$this->recursive = 2;
		$this->Behaviors->attach('Containable');
		
		$parent_message = $this->find('first', array(
				'conditions' => array(
					'OR' => array(
						'Message.user_id' => $uid,
						'Message.author_id' => $uid
					),						
					'Message.id' => $mid
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
		
		$child_messages = $this->find('all', array(
				'conditions' => array(
					'Message.parent_id' => $mid
				),
				'contain' => array(
					'User' => array(
						'Profile'
					),
					'Author' => array(
						'Profile'
					)
				),
				'order' => 'Message.created DESC'
			)
		);
		
		return array_reverse(array_merge($child_messages, array($parent_message)));
	}

	function getRecieved($uid){
		
		$this->recursive = 2;
		$this->Behaviors->attach('Containable');
		
		return $this->find('all', array(
				'conditions' => array(
					'Message.user_id' => $uid,
					'Message.deleted' => false,
					'Message.parent_id' => null
				),
				'contain' => array(
					'User' => array(
						'Profile'
					),
					'Author' => array(
						'Profile'
					),
					'ChildMessage'
				)
			)
		);
	}
	
	function getDeleted($uid){
		
		$this->recursive = 2;
		$this->Behaviors->attach('Containable');
		
		return $this->find('all', array(
				'conditions' => array(
					'Message.user_id' => $uid,
					'Message.deleted' => true
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
	
	function getSent($uid){
		
		$this->recursive = 2;
		$this->Behaviors->attach('Containable');
		
		return $this->find('all', array(
				'conditions' => array(
					'Message.author_id' => $uid,
					'Message.deleted' => false
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
}