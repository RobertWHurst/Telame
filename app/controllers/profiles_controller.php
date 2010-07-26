<?php
class ProfilesController extends AppController {

	//Controller config
	var $name = 'Profiles';
	var $helpers = array('RenderProfile');

	//Before the render of all views in this controller
	function beforeRender() {
		parent::beforeRender();
		$this->set('css_for_layout', 'default.css');
	}

	//A summary of whats new for the user.
	function index() {
		//TODO: Replace fake user with the selected user from the database.
		
		//set up the layout
		$this->set('title_for_layout', 'Telame - Error');
	}
    
	function profile($slug = false) {
		
		//if no user slug is given then get the current user's profile.
		if(!$slug){
			
			//get the current user		
    		$userId = $this->Auth->user('id');
    		
    		$profile = $this->Profile->findByUser_id($userId);
    	}
    	else{
	
			//get the profile
			$profile = $this->Profile->findBySlug($slug);
			
		}
		
		//page title
		$this->set('title_for_layout', "Telame - {$profile['ProfileMeta']['first_name']} {$profile['ProfileMeta']['last_name']}");
		
		//pass the profile data to the view
		$this->set('profile', $profile);
	}
	
	function jx_profile_search(){
	
		//TODO: these results will need to be ranked by relationship, network, secondary relationships, and location, in that order.
		
		//get all the searchable profiles
		$results = $this->Profile->findAllBySearchable(true);
		
		$this->set('results', $results);
	}

}