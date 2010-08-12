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
				'order' => 'Message.created ASC'
			)
		);
		
		array_unshift($child_messages, $parent_message);
		
		return $child_messages;
	}
	
	//NOTE: this is dirty but it works for now...
	function _remove_duplicate_threads($messages){
				
		foreach($messages as $message){
			//get the parent id
			$pid = $message['Message']['parent_id'];
			
			//if the pid is -1 then replace it with the messages own id
			if($pid < 0)
				$pid = $message['Message']['id'];
				
			//add or overwrite the index
			$_messages[$pid] = $message;
		}
		
		return array_values($_messages);
	}

	function getRecieved($uid){
		
		$this->recursive = 2;
		$this->Behaviors->attach('Containable');
					
			$recived = $this->find('all', array(
				'conditions' => array(
					'Message.deleted_by_user' => false,
					'Message.user_id' => $uid
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
			
		$recived = $this->_remove_duplicate_threads($recived);
		
		return $recived;
	}
	
	function getDeleted($uid){
		
		$this->recursive = 2;
		$this->Behaviors->attach('Containable');
	}
	
	function getSent($uid){
		
		$this->recursive = 2;
		$this->Behaviors->attach('Containable');
					
			$sent = $this->find('all', array(
				'conditions' => array(
					'Message.deleted_by_author' => false,
					'Message.author_id' => $uid
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
		
		$sent = $this->_remove_duplicate_threads($sent);
		
		return $sent;
	}
}