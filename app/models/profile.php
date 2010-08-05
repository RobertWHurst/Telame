<?php
class Profile extends AppModel {
	var $name = 'Profile';
	var $belongsTo = array('User');

	var $virtualFields = array(
		'full_name' => 'initcap(first_name) || \' \' || initcap(last_name)'
	);


}