<?php
class LinkRenderHelper extends AppHelper {
	// you can set this to the full path to the wkhtmltoimage program if you like.
	// if you leave it null, or set it to something that we can't execute, we will try and locate it with 'which wkhtmltoimage' below
	var $wkhtmltoimage = null;

	public function link($link, $user, $options = array()) {
		// make sure we have $this->wkhtmltoimage set properly
		if (!$this->findWkFile()) {
			return;
		}

		$options = array_merge(array(
			'width' => 1024,
			'heigh' => 682,
		), $options);

		// generate a random filename
		$filename = rand() . '.jpg';
		// wkhtmltoimage www.example.com /filename.jpg
		exec($this->wkhtmltoimage .
			' --crop-w ' . $options['width'] .
			' --crop-h ' . $options['height'] .
			$link . ' ' .
			DS . 'tmp' . DS . $filename);

		// set to the current user's directory
		$baseDir = USER_DIR . $user['User']['home_dir'] . DS . $user['User']['sub_dir'] . DS . $user['User']['id'] . DS . 'image' . DS;

		// move the temp file to the user's dir
		rename(DS . 'tmp' . DS . $filename, $baseDir . DS . $filename);
	}

	private function findWkFile() {
		// check if the var is null, or not executable
		if (is_null($this->wkhtmltoimage) || !is_executable($this->wkhtmltoimage)) {
			// output of exec stored here
			$output = array();
			// find wkhtmltoimage
			exec('which wkhtmltoimage', $output);
			// make sure it's executable
			if (!is_executable($output[0])) {
				return false;
			}
			// set the global var
			$this->wkhtmltoimage = $output[0];
		}

		return true;
	}

}