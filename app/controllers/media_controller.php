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

	function preview($id) {
		$this->_resize($id, Configure::read('PreviewSize'));
	}

	function profile($id) {
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
					$baseDir = APP . 'users' . DS . $this->currentUser['User']['home_dir'] . DS . $this->currentUser['User']['sub_dir'] . DS . $this->currentUser['User']['id'] . DS . 'images' . DS;

					// user's directory is writable
					if(is_writable($baseDir)) {
						$extension = explode('.', $this->data['Media']['file']['name']);
						$extension = $extension[count($extension)-1];

						$filename = $this->data['Media']['file']['name'] . date('Y-m-d H:i:s') . '.' . $extension;
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
	function _resize($mid, $size) {
		if (empty($mid)) {
		//	$this->cakeError('error404');
		}
		// media view for files
		$this->view = 'Media';

		// find the image
		$media = $this->Media->find('first', array(
			'conditions' => array(
				'Media.id' => $mid
			),
		));

		if ($media && $media['User']['avatar_id'] != -1) {
			// to user's home directory
			$baseDir = APP . 'users' . DS . $media['User']['home_dir'] . DS . $media['User']['sub_dir'] . DS . $media['User']['id'] . DS . 'images' . DS;
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
	
			if ($imageWidth < $imageHeight) {
				// make it wider
				$ar = $imageHeight / $imageWidth;
				$size['height'] = round($size['width'] * $ar);
			} else {
				// make it taller
				$ar = $imageWidth / $imageHeight;
				$size['width'] = round($size['height'] * $ar);
			}

			$cacheFilename = $filename . '-' . $size['height'] . 'x' . $size['width'] . '.jpg';

		} else {
			$filename = 'img.png';
			$baseDir = APP . 'users' . DS . 'system_files' . DS . 'images' . DS;
			$cacheFilename = $filename . '-' . $size['height'] . 'x' . $size['width'] . '.jpg';
			$dir = 'profile' . DS;
			$extension = 'png';
			$name = 'No Image';
		}

		// check for a cached version first
		if (!file_exists($baseDir . 'cache' . DS . $cacheFilename)) {
			// we don't have it, generate it
			if(!$this->Thumb->generateThumb($baseDir, $dir, $filename, $size)) {
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