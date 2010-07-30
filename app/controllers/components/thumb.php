<?php
/**
 * Thumbnail Generator for CakePHP that uses phpThumb (http://phpthumb.sourceforge.net/)
 *
 * @package default
 * @author Nate Constant
 **/

class ThumbComponent{

	/**
	 * The mime types that are allowed for images
	 */
	var $allowed_mime_types = array('image/jpeg','image/pjpeg','image/gif','image/png');

	/**
	 * File system location to save thumbnail to.  ** Must be writable by webserver
	 */

	function startup(&$controller) {
      $this->controller = &$controller;
    }

	/**
	 * This is the method that actually does the thumbnail generation by setting up
	 * the parameters and calling phpThumb
	 *
	 * @return bool Success?
	 * @author Nate Constant
	 **/
	function generateThumb($path, $filename, $size) {
		// Make sure we have the name of the uploaded file and that the Model is specified
		if(empty($path) || empty($filename)){
			return false;
		}
		if (empty($size)) {
			$height = 100;
			$width = 100;
		} else {
			$height = $size['height'];
			$width = $size['width'];
		}

		// verify that the size is greater than 0 ( emtpy file uploaded )
		if(filesize($path . DS . $filename === 0)){
			return false;
		}

		// verify that our file is one of the valid mime types
//		if(!in_array($this->file['type'],$this->allowed_mime_types)){
//			$this->addError('Invalid File type: '.$this->file['type']);
//			return false;
//		}

		// verify that the filesystem is writable, if not add an error to the object
		// dont fail if not and let phpThumb try anyway
		if(!is_writable($path)) {
			return false;
		}

		// Load phpThumb
		App::import('Vendor', 'phpThumb', array('file' => 'phpThumb/phpthumb.class.php'));
		$phpThumb = new phpThumb();

		$phpThumb->setSourceFilename($path . DS . 'profile' . DS . $filename);
		$phpThumb->setParameter('w' ,$width);
		$phpThumb->setParameter('h', $height);
		$phpThumb->setParameter('zc', 1);

		if($phpThumb->generateThumbnail()){
			if(!$phpThumb->RenderToFile($path . DS . 'cache' . DS . $filename . '-' . $height . 'x' . $width . '.jpg')) {
				return false;
			}
		} else {
			return false;
		}
		return true;
	}

}
?>