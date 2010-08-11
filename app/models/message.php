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
		),
		'ParentMessage' => array(		
			'className' => 'Message',		
			'foreignKey' => 'parent_id'
		)
	);

	function getMessageThread($uid, $pid){
		
		$this->recursive = 2;
		$this->Behaviors->attach('Containable');
		
		$parent_message = $this->find('first', array(
				'conditions' => array(
					'OR' => array(
						'Message.user_id' => $uid,
						'Message.author_id' => $uid
					),					
					'Message.id' => $pid
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
					'Message.parent_id' => $pid
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
		
		$child_messages[] = $parent_message;
		
		return array_reverse($child_messages);
	}
	
	function _get_threads($uid){
		
		$this->recursive = 1;
		$this->Behaviors->attach('Containable');
			
		$threads = $this->find('all', array(
				'conditions' => array(
					'Message.parent_id' => -1,
					'Message.deleted' => false,
					'OR' => array(
						'Message.user_id' => $uid,
						'Message.author_id' => $uid
					)
				),				
				'fields' => array(
					'id'
				)
			)
		);
		
		foreach($threads as $thread)
			$thread_ids[] = $thread['Message']['id'];
			
		return $thread_ids;
	}

	function getRecieved($uid){
		
		$this->recursive = 2;
		$this->Behaviors->attach('Containable');
		
		$thread_ids = $this->_get_threads($uid);
		$thread_ids[] = -1;
		
		krumo($thread_ids);
					
			$recived = $this->find('all', array(
				'conditions' => array(
					'Message.deleted' => false,
					'Message.user_id' => $uid,
					'Message.parent_id' => $thread_ids
				),
				'order' => 'Message.created DESC',
				'contain' => array(
					'User' => array(
						'Profile'
					),
					'Author' => array(
						'Profile'
					),
					'ParentMessage'
				)				
			));
		
		//}
		
		//foreach($_recived as $message)
			//$recived[] = $message;
		
		return $recived;
	}
	
	function getDeleted($uid){
		
		$this->recursive = 2;
		$this->Behaviors->attach('Containable');
	}
	
	function getSent($uid){
		
		$this->recursive = 2;
		$this->Behaviors->attach('Containable');
	}
}