<?php
class BreweryAppController extends AppController {
	var $components = array(
		'Auth' => array(
			'loginAction' => array(
				'controller' => 'users',
				'action' => 'login',
				'plugin' => false,
				'admin' => false,
			),
		)
	);


	function beforeFilter() {
		parent::beforeFilter();

	}

	function beforeRender() {
		parent::beforeRender();
	}

}