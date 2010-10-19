<?php
// Used for subdomain routing 
$urlParts = explode('.', env('HTTP_HOST')); 

// send brewery.telame.com to brewery plugin
if ($urlParts[0] == 'brewery') {
	Router::connect('/login', array('controller' => 'users', 'action' => 'login', 'plugin' => null));
	Router::connect('/logout', array('controller' => 'users', 'action' => 'logout'));


	// Must be last, it's a catchall
	Router::connect('/*', array('plugin' => 'brewery', 'controller' => 'brewery_projects', 'action' => 'index'));	
}