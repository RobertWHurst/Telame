<?php
class MediaController extends AppController {

	var $components = array('Thumb');

//---------------------------- Image Retrieval Functions ----------------------------//

	function avatar($id) {
		$this->_resize($id, Configure::read('AvatarSize'));
	}

	function thumb($id) {
		$this->_resize($id, Configure::read('ThumbSize'));
	}

	function profile($id) {
		$this->_resize($id, Configure::read('ProfileSize'));
	}


//---------------------------- Upload Functions ----------------------------//

	function upload() {
		if (empty($this->data)) {

			$user = $this->Media->User->getProfile($this->currentUser['User']['slug']);
			$this->set(compact('user'));
		}
	}


//---------------------------- Private Functions ----------------------------//

	// this function fetches the user's avatar
	function _resize($mid, $size) {
		if (empty($mid)) {
		//	$this->cakeError('error404');
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

		if (!$media || $media['User']['avatar_id'] == -1) {
			$baseDir = APP . 'users' . DS . 'system_files' . DS . 'images' . DS . 'profile' . DS;
		} else {
			// to user's home directory
			$baseDir = APP . 'users' . DS . $media['User']['home_dir'] . DS . $media['User']['sub_dir'] . DS . $media['User']['id'] . DS . 'images' . DS;
		}

		// profile or gallery, etc...
		$dir = $media['Media']['path'] . DS;
		// filename
		$filename = trim($media['Media']['filename']) . '.' . $media['Media']['type'];
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
			'name' => $media['User']['slug'],
			'download' => false,
			'extension' => $media['Media']['type'],
			'path' => $baseDir . 'cache' . DS,
			'cache' => '5 days',
		);
		$this->set($params);
	}

}