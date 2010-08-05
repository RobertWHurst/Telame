<?php
class Album extends AppModel {
	var $name = 'Album';
	var $belongsTo = array('User');
	var $hasMany = array('Media');

	function getAlbums($uid) {
		$this->recursive = 2;
		$this->Behaviors->attach('Containable');
		return $this->find('all', array('conditions' => array('user_id' => $uid)));
	}
}