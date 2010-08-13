<?php
class MediaController extends AppController {

	var $components = array('Thumb');

	function avatar($id) {
		$this->_resize($id, Configure::read('AvatarSize'));
	}

	function thumb($id) {
		$this->_resize($id, Configure::read('ThumbSize'));	
	}

	function profile($id) {
		$this->_resize($id, Configure::read('ProfileSize'));
	}


//---------------------------- Private Functions ----------------------------//

	// this function fetches the user's avatar
	function _resize($mid = null, $size) {
		if (empty($mid)) {
			$this->cakeError('error404');
		}
		// media view for files
		$this->view = 'Media';

		// we don't need all the associations
//		$this->Media->User->Behaviors->attach('Containable');
		$media = $this->Media->find('first', array(
			'conditions' => array(
				'Media.id' => $mid
			),
		));
		
		$user = $this->Media->User->find('first', array(
			'conditions' => array(
				'User.id' => $media['User']['id'],
			)
		));
		
		if (!$user || $user['User']['avatar_id'] == -1) {
			$baseDir = APP . 'users' . DS . 'system_files' . DS . 'images' . DS;
		} else {
			// to user's home directory
			$baseDir = APP . 'users' . DS . $user['User']['home_dir'] . DS . $user['User']['sub_dir'] . DS . $user['User']['id'] . DS . 'images' . DS;		
		}

		// profile or gallery, etc...
		$dir = $user['Media']['path'] . DS;
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

		$params = array(
			'id' => $cacheFilename,
			'name' => $user['User']['slug'],
			'download' => false,
			'extension' => $user['Media']['type'],
			'path' => $baseDir . 'cache' . DS,
			'cache' => '5 days',
		);
		$this->set($params);
	}

}