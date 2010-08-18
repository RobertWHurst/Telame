<?php
class SettingsController extends AppController{

	var $uses = array();

	function basic() {

		//get the user id
		$id = $this->currentUser['User']['id'];

		if(empty($this->data)){
	
			//set the layout
			$this->layout = 'tall_header_w_sidebar';

			$user = $this->currentUser;


			$this->set(compact('user'));

			//save the users new settings

			//TODO: ACL STUFF HERE

		}

		//get the users current settings

		//TODO: ACL STUFF HERE
	}

	function profile() {

		//get the user id
		$id = $this->currentUser['User']['id'];

		if(empty($this->data)){
	
			//set the layout
			$this->layout = 'tall_header_w_sidebar';

			$user = $this->currentUser;


			$this->set(compact('user'));

			//save the users new settings

			//TODO: ACL STUFF HERE

		}

		//get the users current settings

		//TODO: ACL STUFF HERE
	}
	
}