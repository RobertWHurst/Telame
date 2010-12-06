<?php
// Used for subdomain routing
$urlParts = explode('.', env('HTTP_HOST'));

// send brewery.telame.com to brewery plugin
if (in_array('brew1', $urlParts) || in_array('brew2', $urlParts) || in_array('brewery', $urlParts)) {
	Router::connect('/login', array('controller' => 'users', 'action' => 'login', 'plugin' => null));
	Router::connect('/logout', array('controller' => 'users', 'action' => 'logout', 'plugin' => null));

	Router::connect('/like/*', array('plugin' => 'brewery', 'controller' => 'brewery_projects', 'action' => 'like'));
	Router::connect('/dislike/*', array('plugin' => 'brewery', 'controller' => 'brewery_projects', 'action' => 'dislike'));

	Router::connect('/add', array('plugin' => 'brewery', 'controller' => 'brewery_projects', 'action' => 'add'));
	Router::connect('/edit/*', array('plugin' => 'brewery', 'controller' => 'brewery_projects', 'action' => 'edit'));

	// Must be last, it's a catchall
	Router::connect('/*', array('plugin' => 'brewery', 'controller' => 'brewery_projects', 'action' => 'index'));

}