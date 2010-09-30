<?php
App::import('Core', 'Helper');

class AppHelper extends Helper {

	function url($url = null, $full = false) {
		if (isset($this->params['admin']) && is_array($url) && !isset($url['admin'])) {
			$url['admin'] = false;
		}
		return parent::url($url, $full);
	}
}