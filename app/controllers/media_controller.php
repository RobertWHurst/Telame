<?php
class MediaController extends AppController {

	public $components = array('Thumb', 'ScaleTool');

//---------------------------- Image Retrieval Functions ----------------------------//

	public function avatar($id = false) {
		$size = Configure::read('AvatarSize');
		$this->_resize($id, $size, null, array(
			// specifying 'w' and 'h' overrides the scale tools work..
			'w' => $size['width'],
			'h' => $size['height'],
			'zc' => 1,
		));
	}

	public function comment($id = false) {
		$size = Configure::read('CommentSize');
		$this->_resize($id, $size, null, array(
			// specifying 'w' and 'h' overrides the scale tools work..
			'w' => $size['width'],
			'h' => $size['height'],
			'zc' => 1,
		));
	}

	public function large($id = false) {
		$this->_resize($id, Configure::read('LargeSize'), 'fit');
	}

	public function news($id = false) {
		$size = Configure::read('NewsSize');
		$this->_resize($id, $size, null, array(
			// specifying 'w' and 'h' overrides the scale tools work..
			'w' => $size['width'],
		));
	}

	public function preview($id = false) {
		$this->_resize($id, Configure::read('PreviewSize'), 'fit');
	}

	public function profile($id = false){
		$this->_resize($id, Configure::read('ProfileSize'), 'fill');
	}

	public function single($id = false) {
		$this->_resize($id, Configure::read('SingleSize'), 'fill', array(
			'zc' => 1
		));
	}

	public function thumb($id = false) {
		$this->_resize($id, Configure::read('ThumbSize'), 'fill');
	}

//---------------------------- Management Functions ----------------------------//

	public function delete($id = null) {
		if (is_null($id)) {
			$this->Session->setFlash(__('media_not_deleted_no_id', true));
			$this->redirect($this->referer());
			exit;
		}
		$id = intval($id);
		$media = $this->Media->find('first', array(
			'conditions' => array(
				'Media.id' => $id, 
				'Media.user_id' => $this->currentUser['User']['id']
			),
			'contain' => array(
				'User'
			)
		));

		if ($media) {
			if ($this->Media->delete($id)) {
				$type = explode('/', $this->data['Media']['file']['type']);
				$type = $type[0];

				@unlink(USER_DIR . $media['User']['home_dir'] . DS . $media['User']['sub_dir'] . DS . $type . DS . $media['Media']['filename']);
				$this->loadModel('WallPost');
				$wp = $this->WallPost->find('first', array('conditions' => array('WallPost.user_id' => $media['User']['id'], 'WallPost.type' => 'media', 'WallPost.model_id' => $media['Media']['id'])));
				$this->WallPost->delete($wp['WallPost']['id']);
				$this->Session->setFlash(__('media_deleted', true));
			} else {
				$this->Session->setFlash(__('media_not_deleted_error', true));
			}
		} else {
			$this->Session->setFlash(__('media_not_deleted_not_owner', true));
		}	
		$this->redirect($this->referer());
		exit;
	}

	public function upload($aid = false) {
		if (empty($this->data)) {
			$this->layout = 'profile';
			$user = $this->Media->User->getProfile($this->currentUser['User']['slug']);
			$albums = $this->Media->Album->getAlbumList($this->currentUser['User']['id']);

			$aid = ($aid ? $aid : false);

			$this->set(compact('aid', 'albums', 'user'));
		} else {
			// no errors reported
			if (!$this->data['Media']['file']['error']) {
				// check if they own the album they're trying to upload to
				if ($this->Media->Album->isAlbumOwner($this->currentUser['User']['id'], $this->data['Media']['album'])) {
					// file type is allowed
					if (in_array($this->data['Media']['file']['type'], Configure::read('AllowedFileTypes'))) {
						$type = explode('/', $this->data['Media']['file']['type']);
						$type = $type[0];
					
						// Full path to store user's images
						$baseDir = USER_DIR . $this->currentUser['User']['home_dir'] . DS . $this->currentUser['User']['sub_dir'] . DS . $this->currentUser['User']['id'] . DS . $type . DS;

						// user's directory is writable
						if(is_writable($baseDir)) {
							$extension = explode('.', $this->data['Media']['file']['name']);
							$extension = $extension[count($extension)-1];

							$filename = $this->data['Media']['file']['name'] . '_' . date('Y-m-d_H-i-s') . '.' . $extension;
							// file does not exist already, otherwise we need to rename it something else
							if (!file_exists($baseDir . $filename)) {
								rename($this->data['Media']['file']['tmp_name'], $baseDir . $filename);

								// save the album cover image
								$albumCover = !$this->Media->inAlbum($this->data['Media']['album']);
		
								$this->Media->create();
								$data['Media']['user_id'] = $this->currentUser['User']['id'];
								$data['Media']['filename'] = $filename;
								$data['Media']['album_id'] = $this->data['Media']['album'];
								$data['Media']['path'] = null;
								$data['Media']['size'] = $this->data['Media']['file']['size'];
								$data['Media']['created'] = date('Y-m-d H:i:s');
								$data['Media']['type'] = $this->data['Media']['file']['type'];
								$data['Media']['title'] = $this->data['Media']['title'];
								$this->Media->save($data);
								
								$mid = $this->Media->id;
					
								if ($albumCover) {
									$this->Media->Album->setAlbumCover($this->data['Media']['album'], $this->Media->id, $this->currentUser['User']['id']);
								}

								$albumSlug = $this->Media->Album->getSlugFromId($this->data['Media']['album']);
								
								$this->loadModel('WallPost');
								$data['WallPost']['post'] = Sanitize::clean($this->data['Media']['title']);
								$data['WallPost']['author_id'] = $this->currentUser['User']['id'];
								$data['WallPost']['user_id'] = $this->currentUser['User']['id'];
								$data['WallPost']['model_id'] = $mid;

								$this->WallPost->add($data, array('type' => 'media', 'class' => 'wall_post'));

								
								$this->redirect('/album/' . $this->currentUser['User']['slug'] . '/' . $albumSlug);
								exit;

							} // an else should never occur..
						} // can't write, somebody fucked something up
					} else { // file extension not valid
						// return new error
						return false;
					}
				}
			}
			$this->Session->setFlash(__('media_upload_failed', true), 'default', array('class' => 'error'));
			$this->redirect(array('controller' => 'albums', 'action' => 'album', $this->data['Media']['album_id']));
			exit;
		}

	}


//---------------------------- Private Functions ----------------------------//

	// this function fetches the user's avatar
	private function _resize($mid, $size, $mode = null, $options = array()) {
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
			$type = explode('/', $media['Media']['type']);
			$type = $type[0];

			$baseDir = USER_DIR . $media['User']['home_dir'] . DS . $media['User']['sub_dir'] . DS . $media['User']['id'] . DS . $type . DS;
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

			if (!is_null($mode)) {
				$this->ScaleTool->setMode($mode);
				$this->ScaleTool->setBox($size['height'], $size['width']);
				$size = $this->ScaleTool->getNewSize($imageHeight, $imageWidth);
			}

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
			'extension' => 'jpg',
			'path' => $baseDir . 'cache' . DS,
			'cache' => '5 days',
		);
		$this->set($params);
	}

}