<?php
class Media extends AppModel {
	var $belongsTo = array(
		'Album', 
		'User'
	);

// -------------------- Custom functions

	// takes an album_id and finds out if there is media in that album
	function inAlbum($aid) {
		$this->recursive = -1;
		$media = $this->find('first', array('conditions' => array('Media.album_id' => $aid)));
		if (!$media) {
			return false;
		} else {
			return true;
		}
	}


}