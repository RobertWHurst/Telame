<?php
class AlbumsController extends AppController {

	function beforeFilter() {
		parent::beforeFilter();
		//add css and js that is common to all the actions in this controller
		$this->Includer->add('css', array(
			'base',
			'tall_header',
			'main_sidebar'
		));
		$this->Includer->add('script', array(
			'jquery',
			'base',
			'main_sidebar',
		));
	}

	//Before the render of all views in this controller
	function beforeRender() {
		//run the before render in the app controller
		parent::beforeRender();
		//set the css and script for the view
		$this->set('css_for_layout', $this->Includer->css());
		$this->set('script_for_layout', $this->Includer->script());
	}

	function albums($slug = false, $title = false) {
		$albumMedia = array();
		$allAlbums = array();
		$currentAlbum = array();

		// get user id
		if (!$slug) {
			$slug = $this->currentUser['User']['slug'];
		}

		// get the user's info based on their slug
		$user = $this->Album->User->getProfile($slug);

		if (!$user) {
            $this->redirect($this->referer(array('action' => 'index')));
            exit;
		}

		// get all albums
		$allAlbums = $this->Album->getAlbums($user['User']['id']);

		if ($title) {
			$aid = $this->Album->getAlbumId($user['User']['id'], $title);
			$albumMedia = $this->Album->getMedia($aid);
			$currentAlbum = $this->Album->getAlbumInfo($aid);
		}
	
		$this->set(compact('currentAlbum', 'allAlbums', 'albumMedia', 'user', 'slug'));
	}

}
