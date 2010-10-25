<?php
class LinkRenderHelper extends AppHelper {
	var $wkhtmltoimage = null;

	public function link($link, $user) {
		// make sure we have $this->wkhtmltoimage set properly
		if (!$this->findWkFile()) {
			return;
		}
		// generate a random filename
		$filename = rand() . '.jpg';
		// wkhtmltoimage www.example.com /filename.jpg
		exec($this->wkhtmltoimage . ' ' . $link . ' ' . DS . 'tmp' . DS . $filename);

		// set to the current user's directory
		$baseDir = USER_DIR . $user['User']['home_dir'] . DS . $user['User']['sub_dir'] . DS . $user['User']['id'] . DS . 'image' . DS;

		// move the temp file to the user's dir
		rename(DS . 'tmp' . DS . $filename, $baseDir . DS . $filename);
	}

	private function findWkFile() {
		if (is_null($this->wkhtmltoimage) || !is_executable($this->wkhtmltoimage)) {
			$output = array();
			exec('which wkhtmltoimage', $output);
			$this->wkhtmltoimage = $output[0];
			if (!is_executable($this->wkhtmltoimage)) {
				return false;
			}
		}
		
		return true;
	}

}