<?php
class Country extends AppModel {
	var $hasMany = array('Profile');
	var $primaryKey = 'country_id';

	function getList() {
		$this->recursive = -1;
		return $this->find('list', array('fields' => array('country_id', 'name')));
	}

}