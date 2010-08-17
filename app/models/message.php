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

	var $hasMany = array(
		'ChildMessage' => array(
			'className' => 'Message',
			'foreignKey' => 'parent_id',
			'excusive' => true
		)
	);
	
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

	function getMessageThread($uid, $pid){
		$pid = Sanitize::clean(intval($pid));

		$this->recursive = 2;
		$this->Behaviors->attach('Containable');

		$parent_message = $this->find('first', array(
				'conditions' => array(
					'Message.id' => $pid,
					// the following prevents access to messages
					// that do not belong to the passed uid.
					'OR' => array(
						'Message.user_id' => $uid,		
						'Message.author_id' => $uid
					)
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

		if(!$parent_message)
			return false;

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

	function getReceived($uid){
		$uid = Sanitize::clean(intval($uid));

		$this->recursive = 2;
		$this->Behaviors->attach('Containable');

		$received = $this->find('all', array(
			'conditions' => array(
				'Message.deleted_by_user' => false,
				'Message.user_id' => $uid
			),
			//'fields' => array('DISTINCT ON ("Message"."parent_id") "Message"."parent_id" AS "Message__parent_id"', '*'),
			//'order' => array('Message.parent_id'),
			'order' => 'Message.created ASC',
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
		if(!$received) {
			return false;
		}
		//return Set::sort($received, '{n}.Message.created', 'desc');
		return $this->_remove_duplicate_threads($received);
	}

	function getSent($uid){

		$this->recursive = 2;
		$this->Behaviors->attach('Containable');

		$sent = $this->find('all', array(
			'conditions' => array(
				'Message.deleted_by_author' => false,
				'Message.author_id' => $uid
			),
			//'fields' => array('DISTINCT ON ("Message"."parent_id") "Message"."parent_id" AS "Message__parent_id"', '*'),
			//'order' => array('Message.parent_id'),
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

		if(!$sent) {
			return false;
		}
		//return Set::sort($sent, '{n}.Message.created', 'desc');
		return $this->_remove_duplicate_threads($sent);
	}
	
	//returns the relation of a user id to a given message
	function userIs($mid, $uid){		
		$this->recursive = 1;
		$this->Behaviors->attach('Containable');
		$message = $this->find('first', array(
			'conditions' => array(
				'Message.id' => $mid
			),
			'contain' => array(),
			'fields' => array(
				'Message.user_id',
				'Message.author_id'
			)
		));
		
		if($uid == $message['Message']['user_id'])
			return 'user';
		elseif($uid == $message['Message']['author_id'])
			return 'author';
		else
			return false;
	}
	
	function getParent($mid){		
		$this->recursive = 1;
		$this->Behaviors->attach('Containable');
		$message = $this->find('first', array(
			'conditions' => array(
				'Message.id' => $mid
			),
			'contain' => array(),
			'fields' => array(
				'Message.parent_id'
			)
		));
		
		if($message)
			return $message['Message']['parent_id'];
		else
			return false;
	}
}