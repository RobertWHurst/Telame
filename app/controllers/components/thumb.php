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
	function generateThumb($baseDir, $dir, $filename, $size) {
		// Make sure we have the name of the uploaded file and that the Model is specified
		if(empty($baseDir) || empty($filename)){
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
		if(filesize($baseDir . $dir . $filename === 0)){
			Debugger::log('File is empty');
			return false;
		}

		// verify that our file is one of the valid mime types
//		if(!in_array($this->file['type'],$this->allowed_mime_types)){
//			$this->addError('Invalid File type: '.$this->file['type']);
//			return false;
//		}

		// verify that the filesystem is writable, if not add an error to the object
		// dont fail if not and let phpThumb try anyway
		if(!is_writable($baseDir . 'cache')) {
			if(!mkdir($baseDir . 'cache')) {
				Debugger::log($baseDir . 'cache' . ' not writable');
				return false;
			}
		}

		// Load phpThumb
		App::import('Vendor', 'phpThumb', array('file' => 'phpThumb/phpthumb.class.php'));
		$phpThumb = new phpThumb();

		$phpThumb->setSourceFilename($baseDir . $dir . $filename);
		$phpThumb->setParameter('w' ,$width);
		$phpThumb->setParameter('h', $height);
		$phpThumb->setParameter('zc', 1);

		if($phpThumb->generateThumbnail()){
			if(!$phpThumb->RenderToFile($baseDir . 'cache' . DS . $filename . '-' . $height . 'x' . $width . '.jpg')) {
				Debugger::log('Could not render to file');
				return false;
			}
		} else {
			Debugger::log('Could not generate thumbnail');
			return false;
		}
		return true;
	}

}
?>