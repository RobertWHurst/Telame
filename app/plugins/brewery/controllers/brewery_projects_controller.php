<?php
class BreweryProjectsController extends BreweryAppController {

	function beforeFilter() {
		parent::beforeFilter();
	}

	function beforeRender() {
		parent::beforeRender();

	}

	function add() {
		if (!empty($this->data)) {
			$this->data['BreweryProject']['user_id'] = $this->currentUser['User']['id'];
			if ($this->BreweryProject->save($this->data)) {
				$this->Session->setFlash(__('project_added_successfully', true));
			} else {
				$this->Session->setFlash(__('project_adding_failed', true));
			}
			$this->redirect(array('controller' => 'brewery_projects', 'action' => 'index'));
		}
	}

	function index() {
		$projects = $this->BreweryProject->find('all');
		$this->set(compact('projects'));
	}

}