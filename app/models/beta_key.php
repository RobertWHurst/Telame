<?php
class BetaKey extends AppModel {

	public function useBetaKey($email) {
		$key = $this->find('first', array('conditions' => array('email' => null)));
		$this->id = $key['BetaKey']['id'];
		$this->saveField('email', Sanitize::clean($email));

		return $key;
	}

}