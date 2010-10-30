<?php
// http://google.com/a
// admin@telame.com 
class GoogleConsumer extends AbstractConsumer {
	// These are sored in order used in the OAuth sequence
	public $requestToken = null;
	public $authorizeToken = null;
	public $accessToken = null;

	public function __construct() {
		$scope = 'https://www.google.com/m8/feeds/';
		$this->requestToken = array(
			'url' => 'https://www.google.com/accounts/OAuthGetRequestToken', 
			'params' => array('scope' => $scope)
		);

		$this->authorizeToken = 'https://www.google.com/accounts/OAuthAuthorizeToken?oauth_token=';

		$this->accessToken = 'https://www.google.com/accounts/OAuthGetAccessToken';

		// key, secret
		parent::__construct('key', 'secret');
	}
}