<?php
class AlbumsController extends AppController {

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
