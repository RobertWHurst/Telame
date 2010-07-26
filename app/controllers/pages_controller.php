<?php
class PagesController extends AppController {
	var $name = 'Pages';
	var $uses = array();

	function beforeRender() {
		parent::beforeRender();
		//set the css and layout
		$this->set('css_for_layout', 'external.css');
		$this->layout = 'external';
	}

	function home() {
		// Signup php code
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
