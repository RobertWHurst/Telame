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

	function getAlbums($uid) {
		return $this->find('all', array(
			'conditions' => array(
				'Album.user_id' => $uid
			),
		));
	}

	function getAlbumId($uid, $slug) {
		$album = $this->find('first', array('conditions' => array('Album.user_id' => $uid, 'Album.title' => $slug), 'fields' => 'Album.id'));
		return $album['Album']['id'];
	}

	function getAlbumInfo($aid) {
		return $this->find('first', array('conditions' => array('Album.id' => $aid)));
	}

	function getMedia($aid) {
		$this->Media->recursive = -1;
		return $this->Media->find('all', array('conditions' => array('album_id' => $aid)));
	}
}