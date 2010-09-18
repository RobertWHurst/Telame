<?php
class Country extends AppModel {
	var $hasMany = array('Profile');
	var $primaryKey = 'country_id';

}