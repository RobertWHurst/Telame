<?php
class Profile extends AppModel {
	var $belongsTo = array('User');

	var $virtualFields = array(
		'full_name' => 'initcap(Profile.first_name) || \' \' || initcap(Profile.last_name)'
	);

//	function __construct($id = false, $table = null, $ds = null) {
//		parent::__construct($id, $table, $ds);
//		$this->virtualFields['full_name'] = sprintf('initcap(%s.first_name) || \' \' || initcap(%s.last_name)', $this->alias, $this->alias);
//	}

// -------------------- Custom functions

}