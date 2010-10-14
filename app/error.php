<?php
/**
 * error.php
 *
 * custom error handler to redirect invalid urls to homepage
 */
class AppError extends ErrorHandler {

	function missingController($params) {
		$this->controller->redirect('/');
	}

}//AppError
