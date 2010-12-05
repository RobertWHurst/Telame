<?php
class Oauth extends AppModel {

	public $belongsTo = array(
		'User' => array(
			'className' => 'User',
			'foreignKey' => 'user_id',
			'counterCache' => true,
	));

	// check if this has expired, if it has, remove it from the db
	public function checkExpires($uid, $name) {
		$this->recursive = -1;
		$oauth = $this->find('first', array('conditions' => array('Oauth.user_id' => $uid, 'Oauth.service' => $name)));
		if (strtotime('now') >= $oauth['Oauth']['expires'] && !is_null($oauth['Oauth']['expires'])) {
			$this->delete($oauth['Oauth']['id']);
		}
	}

	// remove the oauth info from the db
	public function disconnect($uid, $consumerName) {
		$this->recursive = -1;
		$oauth = $this->find('first', array('conditions' => array('Oauth.user_id' => $uid, 'Oauth.service' => $consumerName)));
		$this->delete($oauth['Oauth']['id']);
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

	public function saveToDb($serviceName, $uid, $accessToken, $method, $expires = null) {
		// see if the user has already done this, if they have, we'll overwrite what we've got stored, if not, create a new record
		$service = $this->find('first', array('conditions' => array('user_id' => $uid, 'service' => ucfirst(strtolower($serviceName)))));

		$expires = (is_null($expires) ? null : strtotime('+' . $expires . ' minutes'));

		// ensure 'method' is clean, lowercase, and what we expect it to be
		$method = strtolower(Sanitize::paranoid($method));
		if (($method != 'read') && ($method != 'write')) {
			$method = 'read';
		}

		// if service above returned false, we need to make a new one, otherwise just overwrite the 'object' field
		if (!$service) {
			$this->create();
			$data = array('Oauth' => array(
				'user_id' => $uid,
				'service' => ucfirst(strtolower($serviceName)),
				'object' => serialize($accessToken),
				'method' => $method,
				'expires' => $expires,
			));
			$this->save($data);
		} else {
			$this->id = $service['Oauth']['id'];
			$this->saveField('object', serialize($accessToken));
		}

	}

}