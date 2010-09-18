<?php
class Profile extends AppModel {
	var $belongsTo = array('Country', 'User');
}