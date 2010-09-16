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
	// enable rss extensions
	Router::parseExtensions('rss');

	// Use Cake's new router class.  Import the 'HomeRoute' lib and let's use it
	App::import('Lib', 'routes/HomeRoute');

// Home page

 	// If the first one returns true (the user is logged in) then rediret to news
	Router::connect('/', array('controller' => 'pages', 'action' => 'news'), array('routeClass' => 'HomeRoute'));
	// False, redirect to signup page
	Router::connect('/', array('controller' => 'pages', 'action' => 'home'));
	
// Site pages

	// Signup and Login/logout
	Router::connect('/login', array('controller' => 'users', 'action' => 'login'));
	Router::connect('/logout', array('controller' => 'users', 'action' => 'logout'));
	Router::connect('/signup/*', array('controller' => 'users', 'action' => 'signup'));
	// Confirm email
	Router::connect('/c/*', array('controller' => 'users', 'action' => 'confirm'));

	// Informational pages
	Router::connect('/features', array('controller' => 'pages', 'action' => 'display', 'features'));
	Router::connect('/pages/*', array('controller' => 'pages', 'action' => 'display'));


// User info
	// Events
	Router::connect('/calendar/add/*', array('controller' => 'events', 'action' => 'add'));
	Router::connect('/calendar/edit/*', array('controller' => 'events', 'action' => 'edit'));
	Router::connect('/calendar/delete/*', array('controller' => 'events', 'action' => 'delete'));
	Router::connect('/calendar/feed/*', array('controller' => 'events', 'action' => 'feed'));
	Router::connect('/calendar/move/*', array('controller' => 'events', 'action' => 'move'));
	Router::connect('/calendar/resize/*', array('controller' => 'events', 'action' => 'resize'));
	Router::connect('/calendar/*', array('controller' => 'events', 'action' => 'calendar'));

	// Friend - add
	Router::connect('/f/a/*', array('controller' => 'groups_users', 'action' => 'addFriend'));
	// Friend list
	Router::connect('/friends/*', array('controller' => 'groups_users', 'action' => 'friendList'));

	// Media
	Router::connect('/albums/new/*', array('controller' => 'albums', 'action' => 'newAlbum'));
	Router::connect('/albums/album/image/*', array('controller' => 'albums', 'action' => 'view'));
	Router::connect('/albums/album/*', array('controller' => 'albums', 'action' => 'album'));
	Router::connect('/albums/*', array('controller' => 'albums', 'action' => 'albums'));

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
	Router::connect('/messages/thread/*', array('controller' => 'messages', 'action' => 'view'));
	Router::connect('/messages/sent', array('controller' => 'messages', 'action' => 'sent'));
	Router::connect('/messages/compose', array('controller' => 'messages', 'action' => 'compose'));
	Router::connect('/messages/s', array('controller' => 'messages', 'action' => 'send_message'));
	Router::connect('/messages/d/*', array('controller' => 'messages', 'action' => 'delete_message'));
	Router::connect('/messages/mu/*', array('controller' => 'messages', 'action' => 'mark_message_unread'));
	Router::connect('/messages', array('controller' => 'messages', 'action' => 'inbox'));
	
	// New user adding
	Router::connect('/p/a/*', array('controller' => 'profiles', 'action' => 'add'));

	// News filters
	Router::connect('/news/*', array('controller' => 'pages', 'action' => 'news'));
	
	// Settings
	Router::connect('/settings', array('controller' => 'settings', 'action' => 'basic'));
	Router::connect('/settings/permissions', array('controller' => 'settings', 'action' => 'permissions'));
	Router::connect('/settings/profile', array('controller' => 'settings', 'action' => 'profile'));
	Router::connect('/s/u/*', array('controller' => 'settings', 'action' => 'gallery'));

	// Wall Posts
	Router::connect('/w/l/*', array('controller' => 'wall_posts', 'action' => 'lists'));
	Router::connect('/w/a/*', array('controller' => 'wall_posts', 'action' => 'add'));
	Router::connect('/w/d/*', array('controller' => 'wall_posts', 'action' => 'delete'));
	Router::connect('/w/like/*', array('controller' => 'wall_posts', 'action' => 'like'));
	Router::connect('/w/dislike/*', array('controller' => 'wall_posts', 'action' => 'dislike'));
	// Wall Posts (Rss)
	Router::connect('/rss/*', array('controller' => 'pages', 'action' => 'news'));
	
	// Wall Posts (Ajax)
	Router::connect('/jx/w/l/*', array('controller' => 'wall_posts', 'action' => 'jx_lists'));
	
// This must be last
	// Profile
	Router::connect('/u/*', array('controller' => 'users', 'action' => 'profile'));
