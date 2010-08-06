<?php
class Album extends AppModel {
	var $name = 'Album';
	var $belongsTo = array('User');
	var $hasMany = array('Media');

	function getAlbums($uid) {
		$this->recursive = -1;
		return $this->find('all', array(
			'conditions' => array(
				'user_id' => $uid
			),
		));
	}

	function getAlbumId($uid, $slug) {
		$this->recursive = -1;
		$album = $this->find('first', array('conditions' => array('user_id' => $uid, 'title' => $slug), 'fields' => 'id'));
		return $album['Album']['id'];
	}

	function getAlbumInfo($aid) {
		return $this->find('first', array('conditions' => array('id' => $aid)));
	}

	function getMedia($aid) {
		$this->Media->recursive = -1;
		return $this->Media->find('all', array('conditions' => array('album_id' => $aid)));
	}
}