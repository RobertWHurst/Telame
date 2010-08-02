<?php
class PagesController extends AppController {
	var $name = 'Pages';
	var $uses = array();
	var $helpers = array('Time');

	function beforeFilter() {
		parent::beforeFilter();

		//allow this entire controller to be accessed without needing to login
		$this->Auth->allow('*');

		//add css and js that is common to all the actions in this controller
		$this->Includer->add('css', array(
			'base',
			'simple_header'
		));
		$this->Includer->add('script', array(
			'jquery',
			'base'
		));
	}

	function beforeRender() {
		parent::beforeRender();

		//set the css and script for the view
		$this->set('css_for_layout', $this->Includer->css());
		$this->set('script_for_layout', $this->Includer->script());

		//set the css and layout
		$this->layout = 'pages';
	}

	function signup() {
		$this->Includer->add('script', array(
			'pages/taglines'
		));
	}

	function news() {
		App::Import('Model', 'Friend');
		$this->Friend = new Friend();
		$friends = $this->Friend->getFriendList(Configure::read('UID'));
		
		App::Import('Model', 'WallPost');
		$this->WallPost = new WallPost();
		
		$wallPosts = $this->WallPost->getWallPosts(null, null, null, $friends);
		$user = $this->currentUser;
		
		$this->set(compact('user', 'wallPosts'));
	}


/**
 * Displays a view
 *
 * @param mixed What page to display
 * @access public
 */
	function display() {
		$path = func_get_args();

		$count = count($path);
		if (!$count) {
			$this->redirect('/');
		}
		$page = $subpage = $title_for_layout = null;

		if (!empty($path[0])) {
			$page = $path[0];
		}
		if (!empty($path[1])) {
			$subpage = $path[1];
		}
		if (!empty($path[$count - 1])) {
			$title_for_layout = Inflector::humanize($path[$count - 1]);
		}
		// add this snippet before the last line
		if (method_exists($this, $page)) {
			$this->$page();
		}
 		$this->set(compact('page', 'subpage', 'title_for_layout'));
		$this->render(implode('/', $path));
	}
}
