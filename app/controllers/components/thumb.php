<?php
/**
 * Thumbnail Generator for CakePHP that uses phpThumb (http://phpthumb.sourceforge.net/)
 *
 * @package default
 * @author Nate Constant
 **/

class ThumbComponent {

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
	function generateThumb($baseDir, $dir, $filename, $size, $options = array()) {
		// Make sure we have the name of the uploaded file and that the Model is specified
		if(empty($baseDir) || empty($filename)){
			Debugger::log('Base directory or filename is empty');
			return false;
		}
		$source = $baseDir . $dir . $filename;

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
		$cacheDir = $baseDir . 'cache' . DS;
		if(!file_exists($cacheDir)) {
			debugger::log('Cache directory doesn\'t exist');
			if(!mkdir($cacheDir)) {
				Debugger::log('Can\' create ' . $cacheDir);
				return false;
			}
		}
		if(!is_writable($cacheDir)) {
			debugger::log($cacheDir . ' not writable');
			chmod($cacheDir, 0777);
		}

		// Load phpThumb
		App::import('Vendor', 'phpThumb', array('file' => 'phpThumb/phpthumb.class.php'));
		$phpThumb = new phpThumb();
		// phpThumb configs
		$phpThumb->setParameter('config_cache_force_passthru', false);

		// image configs
		$phpThumb->setSourceFilename($source);
		$phpThumb->setParameter('w' ,$width);
		$phpThumb->setParameter('h', $height);
		// auto rotate based on exif data
		$phpThumb->setParameter('ar', 'x');

		foreach($options as $key => $val) {
			$phpThumb->setParameter($key, $val);
		}

//		$phpThumb->setParameter('zc', 1);

		if($phpThumb->generateThumbnail()){
			if(!$phpThumb->RenderToFile($cacheDir . $filename . '-' . $height . 'x' . $width . '.jpg')) {
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