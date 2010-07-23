<?php
class ProfileController extends AppController {

	//Controller config
	var $name = 'Profile';
	var $uses = null; //TODO: '$uses=null' can be removed once the db is configured

	//Before the render of all views in this controller
	function beforeRender() {
		parent::beforeRender();
		$this->set('css_for_layout', 'default.css');
	}
	
	//a list of all the user's friends
	function friends(){
		//uses the profile_relationships table
		
		//in order for a 'friend' to apear here the relationship
		//must be mutual. This means that both profiles must have
		//a relationship record for each other. this way we can tell
		//who initisated the relationship, and who to ask to allow
		//or deny it. for a better understanding see the
		//databaseStructure branch in git.
	}
	
	function lists(){
		
		//the 'friends' lists will be rendered here.
	}

}