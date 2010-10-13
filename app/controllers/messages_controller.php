<?php
class MessagesController extends AppController {

	public $helpers = array('Markdown', 'Time');

	public function beforeFilter(){
		parent::beforeFilter();

		//set the layout
		$this->layout = 'tall_header_w_sidebar';
	}

	public function autocomplete($string) {
		$users = $this->Message->User->search($string, $this->currentUser['User']['id'], true);

		//Create the json array
		$rows = array();
		foreach($users as $key => $val) {
			//Create an event entry
			$rows[] = array(
				'id' => $users[$key]['User']['id'],
				'full_name' => $users[$key]['User']['full_name'],
				'slug' => $users[$key]['User']['slug'],
				'email' => $users[$key]['User']['email'],
			);
		}

		// Return as a json array
		Configure::write('debug', 0);
		$this->autoRender = false;
		$this->autoLayout = false;
		$this->header('Content-Type: application/json');
		echo json_encode($rows);
	}


	//THE COMPOSER
	public function compose($slug = false){
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

	//DELETE A MESSAGE (donest actually delete anything, just hides it. intill the other owner deletes it to)(only accepts threads)
	public function delete_message($mid) {
		$isAjax = $this->RequestHandler->isAjax();

		$uid = $this->currentUser['User']['id'];

		//findout how the current user is assocsiated with the message
		$userIs = $this->Message->userIs($mid, $uid);

		//get the parent message id so we can take out all messages at once
		$mpid = $this->Message->getParent($mid);

		if($userIs == 'user') {
		//mark the messages as deleted
			$this->Message->updateAll(
				array(
					'Message.deleted_by_user' => 'true'
				),
				array(
					'OR' => array(
						'Message.id' => $mpid,
						'Message.parent_id' => $mpid
					),
					'Message.user_id' => $uid
				)
			);

			//delete message thread that have been deleted by both the user and the author
			$this->Message->deleteAll(array(
				'OR' => array(
					'Message.user_id' => $this->currentUser['User']['id'],
					'Message.author_id' => $this->currentUser['User']['id']
				),
				'Message.parent_id' => -1,
				'Message.deleted_by_user' => true,
				'Message.deleted_by_author' => true
			));

			if($isAjax) {
				echo 'true';
				exit;
			}
			else
				$this->Session->setFlash(__('message_thread_deleted', true), 'default', array('class' => 'info'));
		}
		elseif($userIs == 'author') {
			$this->Message->updateAll(
				array(
					'Message.deleted_by_author' => 'true'
				),
				array(
					'OR' => array(
						'AND' => array(
							'Message.id' => $mpid,
							'Message.parent_id' =>  -1
						),
						'Message.parent_id' => $mpid
					),
					'Message.author_id' => $uid
				)
			);

			//delete message thread that have been deleted by both the user and the author
			$this->Message->deleteAll(array(
				'OR' => array(
					'Message.user_id' => $this->currentUser['User']['id'],
					'Message.author_id' => $this->currentUser['User']['id']
				),
				'Message.parent_id' => -1,
				'Message.deleted_by_user' => true,
				'Message.deleted_by_author' => true
			));

			if($isAjax){
				echo 'true';
				exit;
			}
			else
				$this->Session->setFlash(__('message_thread_deleted', true), 'default', array('class' => 'info'));
		}

		if($isAjax){
			echo 'false';
		}
		else
			$this->redirect($this->referer());
		exit;
	}

	//THE INBOX
	public function inbox() {

		//get the inbox from the db
		$messages = $this->Message->getReceived($this->currentUser['User']['id']);

		$this->set(compact('messages'));
	}

	//SEND A MESSAGE
	public function send_message(){

		//if there is no data then redirect
		if(empty($this->data)) {
			$this->Session->setFlash(__('message_not_sent', true), 'default', array('class' => 'error'));
			$this->redirect(array('slug' => $this->currentUser['User']['slug'], 'controller' => 'messages', 'action' => 'inbox'));
			exit;
		}
		
		//get each of the slugs user_slugs
		$user_slugs = explode(', ', $this->data['Message']['user_slugs']);
		
		if(is_array($user_slugs)){
			foreach($user_slugs as $slug){
			
				$this->Message->create();
			
				//get the user id via the slug
				$uid = $this->Message->User->getIdFromSlug($slug);
	
				//save the user id and poster id
				$this->Message->set('user_id', $uid);
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
			}
		}
		$this->Session->setFlash(__('message_sent', true), 'default', array('class' => 'info'));
		
		$this->redirect(array('slug' => $this->currentUser['User']['slug'], 'controller' => 'messages', 'action' => 'inbox'));
	}

	//THE SENT MESSAGES
	public function sent(){

		//get the inbox from the db
		$messages = $this->Message->getSent($this->currentUser['User']['id']);

		$this->set(compact('messages'));
	}

	//THE VIEWER
	public function view($id = null){

		//get the inbox from the db
		$messages = $this->Message->getMessageThread($this->currentUser['User']['id'], $id);

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

		$this->Message->updateCount($this->currentUser['User']['id']);
		if(!$messages){
			$this->redirect(array('controller' => 'messages', 'action' => 'inbox'));
			exit;
		}


		$this->set(compact('messages'));
	}
}