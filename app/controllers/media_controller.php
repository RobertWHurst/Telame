<?php
class MediaController extends AppController {

	var $components = array('Thumb');

	function avatar($uid) {
		$this->_resize($uid, Configure::read('AvatarSize'), 'profile');
	}

	function profile($uid) {
		$this->_resize($uid, Configure::read('ProfileSize'), 'profile');
	}


//---------------------------- Private Functions ----------------------------//

	// this function fetches the user's avatar
	function _resize($uid = null, $size, $dir) {
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
		// to user's home directory
		$baseDir = APP . 'users' . DS . $user['User']['home_dir'] . DS . $user['User']['sub_dir'] . DS . $uid . DS . 'images' . DS;
		// profile or gallery, etc...
		$dir .= DS;
		// filename
		$filename = trim($user['Media']['filename']);
		// cached version of filename
		$cacheFilename = $filename . '-' . $size['height'] . 'x' . $size['width'] . '.jpg';

		// check for a cached version first
		if (!file_exists($baseDir . 'cache' . DS . $cacheFilename)) {
			// we don't have it, generate it
			if(!$this->Thumb->generateThumb($baseDir, $dir, $filename, $size)) {
				Debugger::log('Can\'t generate thumbnail');
			}
		}

		// find the file extension
		$temp = explode('.', $filename);
		$extension = $temp[count($temp)-1];
		unset($temp);

		$params = array(
			'id' => $cacheFilename,
			'name' => $user['User']['slug'],
			'download' => false,
			'extension' => $extension,
			'path' => $baseDir . 'cache' . DS,
			'cache' => '5 days',
		);
		$this->set($params);
	}

}
?>