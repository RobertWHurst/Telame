<?php
class Country extends AppModel {
	public $hasMany = array('Profile');
	public $primaryKey = 'country_id';

	public function getList() {
		$this->recursive = -1;
		return $this->find('list', array('fields' => array('country_id', 'name'), 'cache' => 'countryList'));
	}

}