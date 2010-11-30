<?php
// ericfri
class TwitterConsumer extends AbstractConsumer {
	// These are sored in order used in the OAuth sequence
	public $requestToken = null;
	public $authorizeUrl = null;
	public $accessToken = null;

	public function __construct() {
		$this->requestToken = array(
			'url' => 'http://twitter.com/oauth/request_token',
			'params' => array()
		);

		$this->authorizeUrl = 'http://twitter.com/oauth/authorize?oauth_token=';

		$this->accessToken = 'http://twitter.com/oauth/access_token';

		// key, secret
		parent::__construct('key', 'secret');
	}

	public function post(&$accessToken, &$oauthConsumer, $post) {
		$oauthConsumer->post(
			$accessToken->key,
			$accessToken->secret,
			'http://api.twitter.com/1/statuses/update.json',
			array(
				'status' => $post//$text->truncate($this->data['WallPost']['post'], 140)
			)
		);
	}
}