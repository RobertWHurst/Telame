<?php
class PagesController extends AppController {

	public $uses = array();

	public function beforeFilter() {
		parent::beforeFilter();
		// Allows access to certain pages
		$this->Auth->allow('display', 'home');
	}

	public function beforeRender() {
		parent::beforeRender();

	}

	public function features() {
		//set the css and layout
		$this->layout = 'simple_header';

		$this->set('title_for_layout', __('site_name', true));
	}

	public function home() {
		//set the css and layout
		$this->layout = 'simple_header';

		$this->set('title_for_layout', __('site_name', true));
	}

/**
 * Displays a view
 *
 * @param mixed What page to display
 * @access public
 */
	public function display() {
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
