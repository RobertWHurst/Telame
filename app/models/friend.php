<?php
class Friend extends AppModel{
	var $name = 'Friend';
	var $belongsTo = array(
        'User' => array(
        	'className' => 'User',
            'foreignKey' => 'parent_user_id'
        ),
        'Friend' => array(
        	'className' => 'User',
        	'foreignKey' => 'child_user_id'
        )
    );
}