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
			exit;
		}
	}

	function edit($id = null) {
		if (!empty($this->data)) {
			$this->data['BreweryProject']['user_id'] = $this->currentUser['User']['id'];
			if ($this->BreweryProject->save($this->data)) {
				$this->Session->setFlash(__('project_edited_successfully', true));
			} else {
				$this->Session->setFlash(__('project_editing_failed', true));
			}
			$this->redirect(array('controller' => 'brewery_projects', 'action' => 'index'));
			exit;
		}
		if (!is_null($id)) {
			$this->data = $this->BreweryProject->read(null, $id);
		}
	}

	function index() {
		$projects = $this->BreweryProject->find('all', array('order' => 'BreweryProject.id ASC'));
		$this->set(compact('projects'));
	}

}