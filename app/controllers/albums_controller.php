<?php
class AlbumsController extends AppController {

	function albums($slug = false) {
		if ($slug) {
			$uid = $this->Album->User->getIdFromSlug($slug);
		} else {
			$uid = $this->currentUser['User']['id'];
		}
		$albums = $this->Album->getAlbums($uid);
	}

}
