<?php
/* BreweryComments Test cases generated on: 2010-10-18 09:10:54 : 1287418734*/
App::import('Controller', 'Brewery.BreweryComments');

class TestBreweryCommentsController extends BreweryCommentsController {
	var $autoRender = false;

	function redirect($url, $status = null, $exit = true) {
		$this->redirectUrl = $url;
	}
}

class BreweryCommentsControllerTestCase extends CakeTestCase {
	function startTest() {
		$this->BreweryComments =& new TestBreweryCommentsController();
		$this->BreweryComments->constructClasses();
	}

	function endTest() {
		unset($this->BreweryComments);
		ClassRegistry::flush();
	}

}
?>