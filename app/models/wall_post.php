<?php
class WallPost extends AppModel {
	var $name = 'WallPost';
	var $belongsTo = array('User');
}