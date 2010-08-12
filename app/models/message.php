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

	function getMessageThread($pid){

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

		// Put the child messages infront of the parent
		array_unshift($child_messages, $parent_message);

		return $child_messages;
	}

	function getRecieved($uid){

		$this->recursive = 2;
		$this->Behaviors->attach('Containable');

		$recived = $this->find('all', array(
			'conditions' => array(
				'Message.deleted_by_user' => false,
				'Message.user_id' => $uid,
				'Message.parent_id'=> -1,
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
				'Message.author_id' => $uid,
			),
			// More or less hackish than using PHP to check the array and remove dups?
			'fields' => array('DISTINCT ON ("Message"."parent_id") "Message"."parent_id" AS "Message__parent_id"', '*'),
			'order' => array('Message.parent_id', 'Message.created DESC'),
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

		return $sent;
	}
}