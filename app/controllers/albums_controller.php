<?php
class AlbumsController extends AppController {

	var $components = array('Profile');

	function beforeFilter() {
		parent::beforeFilter();
	}

	function beforeRender() {
		parent::beforeRender();
	}

	function albums() {
		//layout
		$this->layout = 'profile';

		$user = $this->Profile->getProfile($this->params['slug']);

		if(!$this->Aacl->checkPermissions($user['User']['id'], $this->currentUser['User']['id'], 'media/images')) {
			$this->Session->setFlash(__('not_allowed_images', true), 'default', array('class' => 'warning'));
			$this->redirect($this->referer());
			exit;
		}

		// get all albums
		$albums = $this->Album->getAlbums($user['User']['id']);

		$this->set(compact('albums', 'user'));
	}

	function album($albumSlug = false){
		//layout
		$this->layout = 'profile';

		$user = $this->Profile->getProfile($this->params['slug']);

		if (!is_numeric($albumSlug)) {
			$aid = $this->Album->getAlbumId($user['User']['id'], $albumSlug);
		} else {
			$aid = intval($albumSlug);
		}
		if (!$aid) {
            $this->redirect($this->referer(array('action' => 'index')));
            exit;
		}

		$album = $this->Album->getMedia($aid);

		$this->set(compact('aid', 'album', 'user'));
	}

	function newAlbum() {

		$isAjax = $this->RequestHandler->isAjax();

		if($isAjax)
			$this->layout = false;

		if(!empty($this->data)) {
			$this->data['Album']['user_id'] = $this->currentUser['User']['id'];
			$this->data['Album']['slug'] = Inflector::slug($this->data['Album']['title']);
			if ($this->Album->save($this->data)) {
				if(!$isAjax) {
					$this->Session->setFlash(__('album_added', true), 'default', array('class' => 'info'));
					$this->redirect('/albums');
				} else {
					echo 'true';
					exit;
				}
			}
			else{
				if(!$isAjax){
					$this->Session->setFlash(__('album_not_added', true), 'default', array('class' => 'info'));
					$this->redirect('/albums');
				}else{
					echo 'false';
					exit;
				}
			}
		} else {
			$user = $this->currentUser;
			$this->set(compact('user'));

			if($isAjax){
				$this->layout = false;
				$this->render('/elements/albums/create');
			}
		}
	}
}
