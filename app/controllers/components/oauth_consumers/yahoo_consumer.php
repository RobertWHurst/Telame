<?php
// telame@ymail.com
class YahooConsumer extends AbstractConsumer {
	// These are sored in order used in the OAuth sequence
	public $requestToken = null;
	public $authorizeToken = null;
	public $accessToken = null;

	public function __construct() {
		$this->requestToken = array(
			'url' => 'https://api.login.yahoo.com/oauth/v2/get_request_token', 
			'params' => array()
		);

		$this->authorizeToken = 'https://api.login.yahoo.com/oauth/v2/request_auth';

		$this->accessToken = 'https://api.login.yahoo.com/oauth/v2/get_token';

		// key, secret
		parent::__construct('key', 'secret');
	}
}