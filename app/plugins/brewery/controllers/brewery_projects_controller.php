<?php
class BreweryProjectsController extends BreweryAppController {

	public function beforeFilter() {
		parent::beforeFilter();
	}

	public function beforeRender() {
		parent::beforeRender();

	}

	public function add() {
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

	public function edit($id = null) {
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

	public function dislike($id) {
		$isAjax = $this->RequestHandler->isAjax();

		$this->BreweryProject->BreweryVote->doVote($id, $this->currentUser['User']['id'], false);

		//if not an ajax call redirect from the referer
		if($isAjax) {
			echo 'true';
			return;
		} else {
			$this->redirect($this->referer());
		 	exit;
		}
	}

	public function index() {
		$projects = $this->BreweryProject->find('all', array('order' => 'BreweryProject.id ASC'));
		$this->set(compact('projects'));

	    $this->layout = 'new_tall_header_w_sidebar';
	}

	public function like($id) {
		$isAjax = $this->RequestHandler->isAjax();

		$this->BreweryProject->BreweryVote->doVote($id, $this->currentUser['User']['id'], true);

		//if not an ajax call redirect from the referer
		if($isAjax) {
			echo 'true';
			return;
		} else {
			$this->redirect($this->referer());
		 	exit;
		}
	}
	

}