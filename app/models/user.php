<?php
class User extends AppModel {
	var $name = 'User';
	var $hasOne = array('Profile');
}