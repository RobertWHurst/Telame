<?php
class Profile extends AppModel {
	var $belongsTo = array('User');

	var $virtualFields = array(
		'full_name' => 'initcap(first_name) || \' \' || initcap(last_name)'
	);

// -------------------- Custom functions

}