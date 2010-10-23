<?php
// Used for subdomain routing 
$urlParts = explode('.', env('HTTP_HOST')); 

// send brewery.telame.com to brewery plugin
if (in_array('brewery', $urlParts)) {
	Router::connect('/login', array('controller' => 'users', 'action' => 'login', 'plugin' => null));
	Router::connect('/logout', array('controller' => 'users', 'action' => 'logout', 'plugin' => null));

	Router::connect('/add', array('plugin' => 'brewery', 'controller' => 'brewery_projects', 'action' => 'add'));	

	// Must be last, it's a catchall
	Router::connect('/*', array('plugin' => 'brewery', 'controller' => 'brewery_projects', 'action' => 'index'));	

}