<?php
class WallPost extends AppModel {
	var $name = 'WallPost';
	var $belongsTo = array(
        'User' => array(
            'className' => 'User',
            'foreignKey' => 'user_id'
        ),
        'PostAuthor' => array(
            'className' => 'User',
            'foreignKey' => 'post_author_id'
        )
    );
}