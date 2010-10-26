<?php
class Media extends AppModel {
	public $belongsTo = array(
		'Album' => array(
			'className' => 'Album',
			'conditions' => array('Media.model' => 'album'),
			'foreignKey' => 'model_id',
		),
		'User'
	);

// -------------------- Custom functions

	// takes an album_id and finds out if there is media in that album
	public function inAlbum($aid) {
		$this->recursive = -1;
		$media = $this->find('first', array('conditions' => array('Media.model' => 'album', 'Media.model_id' => $aid)));
		if (!$media) {
			return false;
		} else {
			return true;
		}
	}


}