<?php
class AlbumsController extends AppController {

	function albums($slug = false) {
		//layout
		$this->layout = 'profile';

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
		$albums = $this->Album->getAlbums($user['User']['id']);
	
		$this->set(compact('albums', 'user'));
	}
	
	function album($slug = false, $albumSlug = false){
			
		//layout
		$this->layout = 'profile';

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
		
		$aid = $this->Album->getAlbumId($user['User']['id'], $albumSlug);
		
		if (!$aid) {
            $this->redirect($this->referer(array('action' => 'index')));
            exit;
		}
		
		$album = $this->Album->getMedia($aid);
		
	
		$this->set(compact('album', 'user'));
	
	}

}
