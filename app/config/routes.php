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
	App::import('Lib', 'routes/SlugRoute');

	Router::connectNamed(array('page'));
	Router::connectNamed(array('query'));
	Router::connectNamed(array('slug'));

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
	// Calendar
	Router::connect('/:slug/calendar/add/*', array('controller' => 'events', 'action' => 'add'), array('routeClass' => 'SlugRoute'));
	Router::connect('/:slug/calendar/edit/*', array('controller' => 'events', 'action' => 'edit'), array('routeClass' => 'SlugRoute'));
	Router::connect('/:slug/calendar/delete/*', array('controller' => 'events', 'action' => 'delete'), array('routeClass' => 'SlugRoute'));
	Router::connect('/:slug/calendar/feed/*', array('controller' => 'events', 'action' => 'feed'), array('routeClass' => 'SlugRoute'));
	Router::connect('/:slug/calendar/move/*', array('controller' => 'events', 'action' => 'move'), array('routeClass' => 'SlugRoute'));
	Router::connect('/:slug/calendar/resize/*', array('controller' => 'events', 'action' => 'resize'), array('routeClass' => 'SlugRoute'));
	Router::connect('/:slug/calendar/*', array('controller' => 'events', 'action' => 'calendar'), array('routeClass' => 'SlugRoute'));

	// Delete account
	Router::connect('/:slug/delete/*', array('controller' => 'settings', 'action' => 'delete'), array('routeClass' => 'SlugRoute'));

	// Friend - add
	Router::connect('/:slug/f/a/*', array('controller' => 'groups_users', 'action' => 'addFriend'), array('routeClass' => 'SlugRoute'));
	// Friend list
	Router::connect('/:slug/friends/:page/*', array('controller' => 'groups_users', 'action' => 'friendList'), array('routeClass' => 'SlugRoute'));
	// 'Hack' for pagination.  keeps the links clean; /slug/friends/2
	Router::connect('/:slug/friends/*', array('controller' => 'groups_users', 'action' => 'friendList'), array('routeClass' => 'SlugRoute'));

	// Media
	Router::connect('/:slug/albums/new/*', array('controller' => 'albums', 'action' => 'newAlbum'), array('routeClass' => 'SlugRoute'));
	Router::connect('/:slug/album/image/*', array('controller' => 'albums', 'action' => 'view'), array('routeClass' => 'SlugRoute'));
	Router::connect('/:slug/album/*', array('controller' => 'albums', 'action' => 'album'), array('routeClass' => 'SlugRoute'));
	Router::connect('/:slug/albums/*', array('controller' => 'albums', 'action' => 'albums'), array('routeClass' => 'SlugRoute'));

	// Media - Avatar
	Router::connect('/i/a/*', array('controller' => 'media', 'action' => 'avatar'));
	// Media - Comment
	Router::connect('/i/c/*', array('controller' => 'media', 'action' => 'comment'));
	// Media - Profile
	Router::connect('/i/p/*', array('controller' => 'media', 'action' => 'profile'));
	// Media - Thumbnail
	Router::connect('/i/t/*', array('controller' => 'media', 'action' => 'thumb'));
	// Media - Preview
	Router::connect('/i/v/*', array('controller' => 'media', 'action' => 'preview'));
	// Media - Single
	Router::connect('/i/s/*', array('controller' => 'media', 'action' => 'single'));
	// Media - upload
	Router::connect('/:slug/media/upload/*', array('controller' => 'media', 'action' => 'upload'), array('routeClass' => 'SlugRoute'));

	// Messaging
	Router::connect('/:slug/messages/thread/*', array('controller' => 'messages', 'action' => 'view'), array('routeClass' => 'SlugRoute'));
	Router::connect('/:slug/messages/sent', array('controller' => 'messages', 'action' => 'sent'), array('routeClass' => 'SlugRoute'));
	Router::connect('/:slug/messages/compose', array('controller' => 'messages', 'action' => 'compose'), array('routeClass' => 'SlugRoute'));
	Router::connect('/:slug/messages/s', array('controller' => 'messages', 'action' => 'send_message'), array('routeClass' => 'SlugRoute'));
	Router::connect('/:slug/messages/d/*', array('controller' => 'messages', 'action' => 'delete_message'), array('routeClass' => 'SlugRoute'));
	Router::connect('/:slug/messages/mu/*', array('controller' => 'messages', 'action' => 'mark_message_unread'), array('routeClass' => 'SlugRoute'));
	Router::connect('/:slug/messages', array('controller' => 'messages', 'action' => 'inbox'), array('routeClass' => 'SlugRoute'));

	// New user adding
	Router::connect('/p/a/*', array('controller' => 'profiles', 'action' => 'add'));

	// News filters
	Router::connect('/news/*', array('controller' => 'pages', 'action' => 'news'));

	// Notifications
	Router::connect('/:slug/notifications', array('controller' => 'notifications', 'action' => 'index'), array('routeClass' => 'SlugRoute'));
	
	// Search
	Router::connect('/search/:query/:page/*', array('controller' => 'users', 'action' => 'search'), array('page' => '[0-9]+'));
	Router::connect('/search/:query/*', array('controller' => 'users', 'action' => 'search'));
	Router::connect('/search/*', array('controller' => 'users', 'action' => 'search'));

	// Settings
	Router::connect('/:slug/settings', array('controller' => 'settings', 'action' => 'basic'), array('routeClass' => 'SlugRoute'));
	Router::connect('/:slug/settings/friends', array('controller' => 'settings', 'action' => 'friends'), array('routeClass' => 'SlugRoute'));
	Router::connect('/:slug/settings/groups/*', array('controller' => 'settings', 'action' => 'groups'), array('routeClass' => 'SlugRoute'));
	Router::connect('/:slug/settings/u/*', array('controller' => 'settings', 'action' => 'gallery'), array('routeClass' => 'SlugRoute'));

	// Wall Posts
	Router::connect('/:slug/w/l/*', array('controller' => 'wall_posts', 'action' => 'lists'), array('routeClass' => 'SlugRoute'));
	Router::connect('/:slug/w/a/*', array('controller' => 'wall_posts', 'action' => 'add'), array('routeClass' => 'SlugRoute'));
	Router::connect('/:slug/w/d/*', array('controller' => 'wall_posts', 'action' => 'delete'), array('routeClass' => 'SlugRoute'));
	Router::connect('/:slug/w/like/*', array('controller' => 'wall_posts', 'action' => 'like'), array('routeClass' => 'SlugRoute'));
	Router::connect('/:slug/w/dislike/*', array('controller' => 'wall_posts', 'action' => 'dislike'), array('routeClass' => 'SlugRoute'));
	// Wall Posts (Rss)
	Router::connect('/rss/*', array('controller' => 'pages', 'action' => 'news'));

	// Wall Posts (Ajax)
	Router::connect('/jx/w/l/*', array('controller' => 'wall_posts', 'action' => 'jx_lists'));

// This must be last
	// Profile
	Router::connect('/*', array('controller' => 'users', 'action' => 'profile'), array('routeClass' => 'SlugRoute'));
