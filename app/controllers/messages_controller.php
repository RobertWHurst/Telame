<?php
class MessagesController extends AppController {

	var $helpers = array('Markdown', 'Time');
	
	function beforeFilter(){
		parent::beforeFilter();
		
		//delete messages that have been deleted by both the user and the author
    	$this->Message->deleteAll(array(
    		'OR' => array(
    			'Message.user_id' => $this->currentUser['User']['id'],
    			'Message.author_id' => $this->currentUser['User']['id']
    		),
    		'Message.deleted_by_user' => true,
    		'Message.deleted_by_author' => true
    	));
	}
	
	//THE INBOX
	function inbox(){
		
		//get the inbox from the db
		$messages = $this->Message->getReceived($this->currentUser['User']['id']);
		
		$this->set(compact('messages'));
	}
	
	//THE VIEWER
	function view($id = null){
	
		//if the id is empty
		
		//get the inbox from the db
		$messages = $this->Message->getMessageThread($id);
		
		//mark the messages as read
    	$this->Message->updateAll(
    		array(
    			'Message.read' => '\'' . date("Y-m-d H:i:s") . '\''
    		),
    		array(
    			'OR' => array(
    				'Message.id' => $id,
    				'Message.parent_id' => $id
    			),
    			'Message.read' => null,
    			'Message.user_id' => $this->currentUser['User']['id']
    		)
    	);
			
		
		if(!$messages){
			$this->redirect(array('controller' => 'messages', 'action' => 'inbox'));
			exit;
		}
			
		
		$this->set(compact('messages'));
	}
	
	//THE SENT MESSAGES
	function sent(){
		
		//get the inbox from the db
		$messages = $this->Message->getSent($this->currentUser['User']['id']);
		
		$this->set(compact('messages'));
	}
	
	//THE COMPOSER
	function compose($slug = false){
		$uid = array();
		
		//get the target user id if there is one
		if($slug){
			//if an array of slugs are given
			if(is_array($slug)){
				foreach($slug as $_slug)					
					$uids[] = $this->User->getIdFromSlug($_slug);
			}
			else
				$uids[] = $this->User->getIdFromSlug($slug);
		}
		
	}
	
	//SEND A MESSAGE
	function send_message(){
	
		//if there is no data then redirect
		if(empty($this->data)) {
			$this->redirect(array('controller' => 'messages', 'action' => 'inbox'));
			exit;
		}
		
		//save the user id and poster id
		$this->Message->set('user_id', $this->data['Message']['user_id']);
		$this->Message->set('author_id', $this->data['Message']['author_id']);
		$this->Message->set('parent_id', $this->data['Message']['parent_id']);

		//save the post content and time
		$this->Message->set('content', $this->data['Message']['content']);
		$this->Message->set('created', date("Y-m-d H:i:s"));
		$this->Message->set('read', null);
		$this->Message->set('deleted_by_user', false);
		$this->Message->set('deleted_by_author', false);
		$this->Message->set('subject', $this->data['Message']['subject']);

		//commit the data to the db
		$this->Message->save();
		
		$this->redirect(array('controller' => 'messages', 'action' => 'view', $this->data['Message']['parent_id']));
	}
	
	//DELETE A MESSAGE
	function delete_message(){
		
	}
	
	//DELETE A MESSAGE
	function mark_message_unread(){
		
	}
	
	function manage_messages(){
		
		krumo($this->data);
		
		die;
		
		//save the user id and poster id
		$this->Message->set('user_id', $this->data['Message']['user_id']);
		$this->Message->set('author_id', $this->data['Message']['author_id']);
		$this->Message->set('parent_id', $this->data['Message']['parent_id']);

		//save the post content and time
		$this->Message->set('content', $this->data['Message']['content']);
		$this->Message->set('created', date("Y-m-d H:i:s"));
		$this->Message->set('read', null);
		$this->Message->set('deleted_by_user', false);
		$this->Message->set('deleted_by_author', false);
		$this->Message->set('subject', $this->data['Message']['subject']);

		//commit the data to the db
		//$this->Message->save();
		
		//$this->redirect(array('controller' => 'messages', 'action' => 'view', $this->data['Message']['parent_id']));
	}
	
	//QUERY FOR POSSIBLE RECIPIENTS
	function jx_recipient(){
		
	}
}