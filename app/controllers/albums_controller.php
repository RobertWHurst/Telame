<?php
class AlbumsController extends AppController {

	function albums($slug = false, $title = false) {
		$albumMedia = array();
		$allAlbums = array();
		$currentAlbum = array();

		// get user id
		if ($slug) {
			$uid = $this->Album->User->getIdFromSlug($slug);
		} else {
			$uid = $this->currentUser['User']['id'];
		}

		// get all albums
		$allAlbums = $this->Album->getAlbums($uid);

		if ($title) {
			$aid = $this->Album->getAlbumId($uid, $title);
			$albumMedia = $this->Album->getMedia($aid);
			$currentAlbum = $this->Album->getAlbumInfo($aid);
		}
		
		$this->set(compact('currentAlbum', 'allAlbums', 'albumMedia'));
	}

}
