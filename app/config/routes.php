<?php
/**
 * Routes configuration
 *
 * In this file, you set up routes to your controllers and their actions.
 * Routes are very important mechanism that allows you to freely connect
 * different urls to chosen controllers and their actions (functions).
 *
 * PHP versions 4 and 5
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright 2005-2010, Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright 2005-2010, Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       cake
 * @subpackage    cake.app.config
 * @since         CakePHP(tm) v 0.2.9
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */
/**
 * Here, we are connecting '/' (base path) to controller called 'Pages',
 * its action called 'display', and we pass a param to select the view file
 * to use (in this case, /app/views/pages/home.ctp)...
 */
	// Use Cake's new router class.  Import the 'HomeRoute' lib and let's use it
	App::import('Lib', 'routes/HomeRoute');

	// Temp
	Router::connect('/am/*', array('controller' => 'users', 'action' => 'addMeta'));

// Home page

 	// If the first one returns true (the user is logged in) then rediret to news
	Router::connect('/', array('controller' => 'notifications', 'action' => 'news'), array('routeClass' => 'HomeRoute'));
	// False, redirect to signup page
	Router::connect('/', array('controller' => 'pages', 'action' => 'home'));
	
// Site pages

	// Signup and Login/logout
	Router::connect('/login', array('controller' => 'users', 'action' => 'login'));
	Router::connect('/logout', array('controller' => 'users', 'action' => 'logout'));
	Router::connect('/signup', array('controller' => 'users', 'action' => 'signup'));

	// Informational pages
	Router::connect('/features', array('controller' => 'pages', 'action' => 'display', 'features'));
	Router::connect('/pages/*', array('controller' => 'pages', 'action' => 'display'));


// User info

	// Media

	Router::connect('/albums/*', array('controller' => 'albums', 'action' => 'albums'));

	Router::connect('/a/vp/*', array('controller' => 'albums', 'action' => 'view'));
	Router::connect('/a/va/*', array('controller' => 'albums', 'action' => 'album'));
	Router::connect('/a/*', array('controller' => 'albums', 'action' => 'albums'));

	// Media - Avatar
	Router::connect('/i/a/*', array('controller' => 'media', 'action' => 'avatar'));
	// Media - Profile
	Router::connect('/i/p/*', array('controller' => 'media', 'action' => 'profile'));
	// Media - Thumbnail
	Router::connect('/i/t/*', array('controller' => 'media', 'action' => 'thumb'));
	// Media - Preview
	Router::connect('/i/v/*', array('controller' => 'media', 'action' => 'preview'));
	// Media - Single
	Router::connect('/i/s/*', array('controller' => 'media', 'action' => 'single'));
	// Media - upload
	Router::connect('/i/u/*', array('controller' => 'media', 'action' => 'upload'));
	
	// Messaging
	Router::connect('/m/vt/*', array('controller' => 'messages', 'action' => 'view'));
	Router::connect('/m/sm', array('controller' => 'messages', 'action' => 'sent'));
	Router::connect('/m/cm', array('controller' => 'messages', 'action' => 'compose'));
	Router::connect('/m/s', array('controller' => 'messages', 'action' => 'send_message'));
	Router::connect('/m/d/*', array('controller' => 'messages', 'action' => 'delete_message'));
	Router::connect('/m/mu/*', array('controller' => 'messages', 'action' => 'mark_message_unread'));
	Router::connect('/m', array('controller' => 'messages', 'action' => 'inbox'));
	
	// Messaging (Ajax)
	//Router::connect('/jx/r', array('controller' => 'messages', 'action' => 'jx_recipient'));
	Router::connect('/jx/m/d', array('controller' => 'messages', 'action' => 'jx_delete_message'));
	Router::connect('/jx/m/mu', array('controller' => 'messages', 'action' => 'jx_mark_message_unread'));	
	
	// New user adding
	Router::connect('/p/a/*', array('controller' => 'profiles', 'action' => 'add'));

	// News filters
	Router::connect('/f/*', array('controller' => 'notifications', 'action' => 'news'));

	// Profile - edit
	Router::connect('/p/e/*', array('controller' => 'profiles', 'action' => 'edit'));
	// Profile - index
	Router::connect('/p/*', array('controller' => 'profiles', 'action' => 'index'));

	// Wall Posts
	Router::connect('/w/l/*', array('controller' => 'wall_posts', 'action' => 'lists'));
	Router::connect('/w/d/*', array('controller' => 'wall_posts', 'action' => 'delete'));

	// Wall Posts (Ajax)
	Router::connect('/jx/w/d/*', array('controller' => 'wall_posts', 'action' => 'jx_delete'));
	Router::connect('/jx/w/l/*', array('controller' => 'wall_posts', 'action' => 'jx_lists'));
	Router::connect('/jx/w/a', array('controller' => 'wall_posts', 'action' => 'add'));
	
// This must be last
	// Profile
	Router::connect('/*', array('controller' => 'users', 'action' => 'profile'));
