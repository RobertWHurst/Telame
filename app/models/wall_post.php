<?php
class WallPost extends AppModel {
	var $name = 'WallPost';
	var $belongsTo = array(
        'User' => array(
            'className' => 'User',
            'foreignKey' => 'user_id'
        ),
        'Poster' => array(
            'className' => 'User',
            'foreignKey' => 'poster_id'
        )
    );
}