<?php
class AlbumsController extends AppController {

	function albums($slug = false, $albumSlug = false) {
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
		$albums['all'] = $this->Album->getAlbums($user['User']['id']);

		if ($albumSlug) {
			$aid = $this->Album->getAlbumId($user['User']['id'], $albumSlug);
			$albums['currentMedia'] = $this->Album->getMedia($aid);
			$albums['current'] = $this->Album->getAlbumInfo($aid);
		}
	
		$this->set(compact('albums', 'user', 'slug'));
	}

}
