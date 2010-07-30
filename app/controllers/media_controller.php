<?php
class MediaController extends AppController {

	// this function fetches the user's avatar
	function avatar($uid = null) {
		if (empty($uid)) {
			$this->cakeError('error404');
		}
		// media view for files
		$this->view = 'Media';
		// we don't need all the associations
		$this->Media->User->Behaviors->attach('Containable');
		$user = $this->Media->User->find('first', array(
			'conditions' => array(
				'User.id' => $uid
			),
			'contain' => array(
				'Media'
			)
		));
		$params = array(
			'id' => trim($user['Media']['filename']),
			'name' => $user['User']['slug'],
			'download' => false,
			'extension' => 'png',
			'path' => APP . 'users' . DS . $uid . DS . 'images' . DS . 'profile' . DS,
			'cache' => '5 days',
	   );
	   $this->set($params);
	}

}
?>