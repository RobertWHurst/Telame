<?php
class Oauth extends AppModel {

	public $belongsTo = array(
		'User' => array(
			'className' => 'User',
			'foreignKey' => 'user_id',
			'counterCache' => true,
	));

	public function saveToDb($serviceName, $uid, $accessToken) {
		// see if the user has already done this, if they have, we'll overwrite what we've got stored, if not, create a new record
		$service = $this->find('first', array('conditions' => array('user_id' => $uid, 'service' => ucfirst(strtolower($serviceName)))));

		if (!$service) {
			$this->create();
			$data = array('Oauth' => array(
				'user_id' => $uid,
				'service' => ucfirst(strtolower($serviceName)),
				'object' => serialize($accessToken),
			));
			$this->save($data);
		} else {
			$this->id = $service['Oauth']['id'];
			$this->saveField('object', serialize($accessToken));
		}

	}

	public function getAccessToken($serviceName, $user) {
		$serviceName = ucfirst(strtolower($serviceName));
		if (is_array($user)) {
			$token = Set::extract('/Oauth[service=' . $serviceName . ']/object', $user);
			return unserialize($token[0]);
		} else {
			$service = $this->find('first', array(
				'conditions' => array(
					'user_id' => $user,
					'service' => $serviceName,
				)
			));
			if ($service) {
				return unserialize($service['Oauth']['object']);
			} else {
				return false;
			}
		}
	}


}