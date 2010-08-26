<?php
class AlbumsController extends AppController {

	function beforeFilter() {
		parent::beforeFilter();
	}

	function beforeRender() {
		parent::beforeRender();
		//layout
		$this->layout = 'profile';
	}

	function albums($slug = false) {
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

		$uid = $user['User']['id'];

		if(!$this->Aacl->checkPermissions($user['User']['id'], $this->currentUser['User']['id'], 'media/images')) {
			$this->Session->setFlash(__('not_allowed_images', true));
			$this->redirect($this->referer());
			exit;
		}

		// get all albums
		$albums = $this->Album->getAlbums($uid);

		$this->set(compact('albums', 'user'));
	}

	function album($slug = false, $albumSlug = false){
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
		$uid = $user['User']['id'];
		$aid = $this->Album->getAlbumId($uid, $albumSlug);

		if (!$aid) {
            $this->redirect($this->referer(array('action' => 'index')));
            exit;
		}

		$album = $this->Album->getMedia($aid);

		$this->set(compact('album', 'user'));
	}

	function newAlbum() {

		if(!empty($this->data)) {
			$this->data['Album']['user_id'] = $this->currentUser['User']['id'];
			$this->data['Album']['slug'] = Inflector::slug($this->data['Album']['title']);
			if ($this->Album->save($this->data)) {
				$this->Session->setFlash(__('album_added', true));
			}
			$this->redirect('/albums');
		} else {
			$user = $this->currentUser;
			$this->set(compact('user'));	
		}
	}
}
