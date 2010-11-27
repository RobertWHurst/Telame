<?php
/* BreweryComment Test cases generated on: 2010-10-18 09:10:45 : 1287419025*/
App::import('Model', 'Brewery.BreweryComment');

class BreweryCommentTestCase extends CakeTestCase {
	function startTest() {
		$this->BreweryComment =& ClassRegistry::init('BreweryComment');
	}

	function endTest() {
		unset($this->BreweryComment);
		ClassRegistry::flush();
	}

}
?>