<?php
class Album extends AppModel {
	public $name = 'Album';
	public $belongsTo = array(
		'Cover' => array(
			'className' => 'Media',
			'foreignKey' => 'cover_id',
		),
		'User'
	);
	public $hasMany = array(
		'Media' => array(
			'className' => 'Media',
			'conditions' => array('Media.model' => 'album'),
			'foreignKey' => 'model_id',
		)
	);

// -------------------- Custom functions

	public function getAlbums($uid) {
		$this->recursive = 0;
		return $this->find('all', array(
			'conditions' => array(
				'Album.user_id' => $uid,
			),
			'order' => 'Album.id'
		));
	}

	public function getAlbumList($uid) {
		$this->recursive = -1;
		return $this->find('list', array(
			'conditions' => array(
				'Album.user_id' => $uid,
			),
			'order' => 'Album.title',
		));
	}

	public function getAlbumId($uid, $slug) {
		$album = $this->find('first', array('conditions' => array('Album.user_id' => Sanitize::clean(intval($uid)), 'Album.slug' => Sanitize::clean($slug)), 'fields' => 'Album.id'));
		return $album['Album']['id'];
	}

	public function getAlbumInfo($aid) {
		return $this->find('first', array('conditions' => array('Album.id' => Sanitize::clean(intval($aid)))));
	}

	// Takes an album ID and returns all the media inside that album
	public function getMedia($aid) {
		$this->Media->recursive = -1;
		return $this->Media->find('all', array('conditions' => array('Media.model' => 'album' , 'Media.model_id' => Sanitize::clean(intval($aid)))));
	}

	public function getSlugFromId($aid) {
		$this->recursive = -1;
		$album = $this->find('first', array('conditions' => array('Album.id' => Sanitize::clean(intval($aid))), 'fields' => array('slug')));
		return $album['Album']['slug'];
	}

	public function isAlbumOwner($uid, $aid) {
		$this->recursive = -1;
		$album = $this->find('first', array('conditions' => array('Album.user_id' => $uid, 'Album.id' => $aid)));
		if ($album) {
			return true;
		} else {
			return false;
		}
	}

	public function isPublic($aid) {
		$album = $this->find('first', array('conditions' => array('Album.id' => $aid), 'fields' => 'Album.public'));
		return $album['Album']['public'];
	}

	// Takes album_id, and image_id, sets that image to that album's cover
	public function setAlbumCover($aid, $iid, $uid) {
		$album = $this->find('first', array('conditions' => array('Album.id' => $aid, 'Album.user_id' => $uid)));
		if ($album) {
			$this->id = intval($aid);
			$this->saveField('cover_id', intval($iid));
			return true;
		} else {
			return false;
		}
	}
}