<?php
class Album extends AppModel {
	var $name = 'Album';
	var $belongsTo = array(
		'Cover' => array(
			'className' => 'Media',
			'foreignKey' => 'cover_id',
		),
		'User'
	);
	var $hasMany = array('Media');

// -------------------- Custom functions

	function getAlbums($uid) {
		$this->recursive = 0;
		return $this->find('all', array(
			'conditions' => array(
				'Album.user_id' => $uid,
			),
			'order' => 'Album.id'
		));
	}

	function getAlbumList($uid) {
		$this->recursive = -1;
		return $this->find('list', array(
			'conditions' => array(
				'Album.user_id' => $uid,
			),
			'order' => 'Album.title',
		));
	}

	function getAlbumId($uid, $slug) {
		$album = $this->find('first', array('conditions' => array('Album.user_id' => Sanitize::clean(intval($uid)), 'Album.slug' => Sanitize::clean($slug)), 'fields' => 'Album.id'));
		return $album['Album']['id'];
	}

	function getAlbumInfo($aid) {
		return $this->find('first', array('conditions' => array('Album.id' => Sanitize::clean(intval($aid)))));
	}

	function getMedia($aid) {
		$this->Media->recursive = -1;
		return $this->Media->find('all', array('conditions' => array('album_id' => Sanitize::clean(intval($aid)))));
	}

	function isAlbumOwner($uid, $aid) {
		$this->recursive = -1;
		$album = $this->find('first', array('conditions' => array('Album.user_id' => $uid, 'Album.id' => $aid)));
		if ($album) {
			return true;
		} else {
			return false;
		}
	}
}