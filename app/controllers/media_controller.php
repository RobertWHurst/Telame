<?php
class MediaController extends AppController {

	var $components = array('Thumb', 'ScaleTool');

//---------------------------- Image Retrieval Functions ----------------------------//

	function avatar($id = false){
		$this->_resize($id, Configure::read('AvatarSize'), array(
			'w' => 60,
			'h' => 60,
			'zc' => 1
		));
	}

	function thumb($id = false){
		$this->_resize($id, Configure::read('ThumbSize'));
	}

	function preview($id = false){
		$this->_resize($id, Configure::read('PreviewSize'));
	}

	function single($id = false){
		$this->_resize($id, Configure::read('SingleSize'), array(
			'w' => 235,
			'h' => 235,
			'zc' => 1
		));
	}

	function profile($id = false){
		$this->_resize($id, Configure::read('ProfileSize'));
	}


//---------------------------- Upload Functions ----------------------------//

	function upload() {
		if (empty($this->data)) {
			$user = $this->Media->User->getProfile($this->currentUser['User']['slug']);
			$this->set(compact('user'));
		} else {
			// no errors reported
			if (!$this->data['Media']['file']['error']) {
				// file type is allowed
				if (in_array($this->data['Media']['file']['type'], Configure::read('AllowedFileTypes'))) {
					// Full path to store user's images
					$baseDir = USER_DIR . $this->currentUser['User']['home_dir'] . DS . $this->currentUser['User']['sub_dir'] . DS . $this->currentUser['User']['id'] . DS . 'images' . DS;

					// user's directory is writable
					if(is_writable($baseDir)) {
						$extension = explode('.', $this->data['Media']['file']['name']);
						$extension = $extension[count($extension)-1];

						$filename = $this->data['Media']['file']['name'] . '_' . date('Y-m-d_H-i-s') . '.' . $extension;
						// file does not exist alread, otherwise we need to rename it something else
						if (!file_exists($baseDir . $filename)) {
							rename($this->data['Media']['file']['tmp_name'], $baseDir . $filename);

							$this->Media->create();
							$data['Media']['user_id'] = $this->currentUser['User']['id'];
							$data['Media']['filename'] = $filename;
							$data['Media']['album_id'] = null;
							$data['Media']['path'] = null;
							$data['Media']['size'] = $this->data['Media']['file']['size'];
							$data['Media']['created'] = date('Y-m-d H:i:s');
							$data['Media']['type'] = $this->data['Media']['file']['type'];
							$this->Media->save($data);

							$this->redirect('/albums');
							exit;

						} // an else should never occur..
					} // can't write, somebody fucked something up
				} else { // file extension not valid
					// return new error
					return false;
				}
			}
		}
	}


//---------------------------- Private Functions ----------------------------//

	// this function fetches the user's avatar
	function _resize($mid, $size, $options = array()) {
		if (empty($mid)) {
		//	$this->cakeError('error404');
		}
		// media view for files
		$this->view = 'Media';

		if ($mid) {
			// find the image
			$media = $this->Media->find('first', array(
				'conditions' => array(
					'Media.id' => $mid
				),
			));

			if (!$this->Media->User->GroupsUser->isFriend($media['User']['id'], $this->currentUser['User']['id'])) {
				$media = false;
			}
		} else {
			$media = false;
		}

		if ($media) {
			// to user's home directory
			$baseDir = USER_DIR . $media['User']['home_dir'] . DS . $media['User']['sub_dir'] . DS . $media['User']['id'] . DS . 'images' . DS;
			// profile or gallery, etc...
			$dir = $media['Media']['path'] . DS;
			// filename
			$extension = explode('.', $media['Media']['filename']);
			$extension = $extension[count($extension)-1];
			$filename = trim($media['Media']['filename']);
			// cached version of filename
			$name = $media['User']['slug'];

			$imageSize = getimagesize($baseDir . $dir . $filename);
			$imageWidth = $imageSize[0];
			$imageHeight = $imageSize[1];

			$this->ScaleTool->setMode('fill');
			$this->ScaleTool->setBox($size['height'], $size['width']);
			$size = $this->ScaleTool->getNewSize($imageHeight, $imageWidth);

			$cacheFilename = $filename . '-' . $size['height'] . 'x' . $size['width'] . '.jpg';

		} else {
			$filename = 'img.png';
			$baseDir = USER_DIR . 'system_files' . DS . 'images' . DS;
			$cacheFilename = $filename . '-' . $size['height'] . 'x' . $size['width'] . '.jpg';
			$dir = 'profile' . DS;
			$extension = 'png';
			$name = 'No Image';
		}

		// check for a cached version first
		if(!file_exists($baseDir . 'cache' . DS . $cacheFilename)) {
			// we don't have it, generate it
			if(!$this->Thumb->generateThumb($baseDir, $dir, $filename, $size, $options)) {
				Debugger::log('Can\'t generate thumbnail');
			}
		}

		$params = array(
			'id' => $cacheFilename,
			'name' => $name,
			'download' => false,
			'extension' => $extension,
			'path' => $baseDir . 'cache' . DS,
			'cache' => '5 days',
		);
		$this->set($params);
	}

}