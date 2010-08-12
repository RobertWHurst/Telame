<?php
class MessagesController extends AppController {

	//THE INBOX
	function inbox(){
		
		//get the inbox from the db
		$messages = $this->Message->getRecieved($this->currentUser['User']['id']);
		
		$this->set(compact('messages'));
	}

	//THE VIEWER
	function view($id){
		
		//get the inbox from the db
		$messages = $this->Message->getMessageThread($id);
		
		$this->set(compact('messages'));
	}
	
	//THE SENT MESSAGES
	function sent(){
		
		//get the inbox from the db
		$messages = $this->Message->getSent($this->currentUser['User']['id']);
		
		$this->set(compact('messages'));
	}
	
	//THE DELETED MESSAGES
	function deleted(){
		
		//get the inbox from the db
		$messages = $this->Message->getDeleted($this->currentUser['User']['id']);
		
	}
	
	//THE COMPOSER
	function compose($slug = false){
		$uids = array();
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
		
		//foreach of the target users
		foreach($uids as $uid){
			$this->User->getProfile($uid);
		}
		
	}
	
	//SEND A MESSAGE
	function send_message(){
		
	}
	
	//QUERY FOR POSSIBLE RECIPIENTS
	function jx_recipient(){
		
	}
}