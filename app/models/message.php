<?php
class Message extends AppModel {
	var $belongsTo = array(
		'User' => array(
			'className' => 'User',
			'foreignKey' => 'user_id',
			'counterCache' => true,
			'counterScope' => array('Message.read' => NULL)
		),
		'Author' => array(
			'className' => 'User',
			'foreignKey' => 'author_id',
		),
		'ParentMessage' => array(
			'className' => 'Message',
			'foreignKey' => 'parent_id',
		)
	);

	var $hasMany = array(
		'ChildMessage' => array(
			'className' => 'Message',
			'foreignKey' => 'parent_id',
			'excusive' => true
		)
	);

	function getMessageThread($pid){
		$pid = Sanitize::clean(intval($pid));

		$this->recursive = 2;
		$this->Behaviors->attach('Containable');

		$parent_message = $this->find('first', array(
				'conditions' => array(
					'Message.id' => $pid,
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
			'fields' => array('DISTINCT ON ("Message"."parent_id") "Message"."parent_id" AS "Message__parent_id"', '*'),
			'order' => array('Message.parent_id'),
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
		return Set::sort($received, '{n}.Message.created', 'desc');
	}

	function getSent($uid){

		$this->recursive = 2;
		$this->Behaviors->attach('Containable');

		$sent = $this->find('all', array(
			'conditions' => array(
				'Message.deleted_by_author' => false,
				'Message.author_id' => $uid
			),
			'fields' => array('DISTINCT ON ("Message"."parent_id") "Message"."parent_id" AS "Message__parent_id"', '*'),
			'order' => array('Message.parent_id'),
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
		return Set::sort($sent, '{n}.Message.created', 'desc');
	}
}