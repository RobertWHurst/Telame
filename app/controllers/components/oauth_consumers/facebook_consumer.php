<?php
// admin@telame.com 
class FacebookConsumer extends AbstractConsumer {
	// These are sored in order used in the OAuth sequence
	public $requestToken = null;
	public $authorizeUrl = null;
	public $accessToken = null;

	public function __construct() {
		$this->requestToken = array(
			'url' => 'https://graph.facebook.com/oauth/authorize', 
			'params' => array(
				'client_id' => '140865389294309',
				'scope' => 'publish_stream',
				'redirect_uri' => 'http://' . env('SERVER_NAME') . '/services/oauth_callback/facebook/',
			)
		);

		$this->authorizeUrl = 'https://graph.facebook.com/oauth/authorize?redirect_uri=http://' . env('SERVER_NAME') . '/services/oauth_callback/facebook/&client_id=140865389294309';

		$this->accessToken = 'https://graph.facebook.com/oauth/access_token';

		// key, secret
		parent::__construct('key', 'secret');
	}
}