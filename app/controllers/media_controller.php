<?php
class MediaController extends AppController {

	var $components = array('Thumb');

	function avatar($uid) {
		$this->resize($uid, Configure::read('AvatarSize'), 'profile');
	}

	function profile($uid) {
		$this->resize($uid, Configure::read('ProfileSize'), 'profile');
	}

	// this function fetches the user's avatar
	function resize($uid = null, $size, $dir) {
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
		$imgPath = APP . 'users' . DS . $user['User']['home_dir'] . DS . $user['User']['sub_dir'] . DS . $uid . DS . 'images' . DS;
		$filename = trim($user['Media']['filename']);
		if(!$this->Thumb->generateThumb($imgPath, $filename, $size)) {
			return 'error';
		}

		$params = array(
			'id' => $filename . '-' . $size['height'] . 'x' . $size['width'] . '.jpg',
			'name' => $user['User']['slug'],
			'download' => false,
			'extension' => 'png',
			'path' => $imgPath . 'cache' . DS,
			'cache' => '5 days',
	   );
	   $this->set($params);
	}

}
?>