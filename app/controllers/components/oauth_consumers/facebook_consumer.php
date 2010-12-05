<?php
// admin@telame.com
class FacebookConsumer extends AbstractConsumer {
	// These are sored in order used in the OAuth sequence
	public $requestToken = null;
	public $authorizeUrl = null;
	public $accessToken = null;
	public $expires = null;

	private $clientId = '';
	private $key = '';
	private $secret = '';

	public function __construct() {
		$this->requestToken = array(
			'url' => 'https://graph.facebook.com/oauth/authorize',
			'params' => array(
				'client_id' => $this->clientId,
				'redirect_uri' => 'http://' . env('SERVER_NAME') . '/services/oauth_callback/facebook/',
				'type' => 'user_agent',
//				'scope' => 'publish_stream',
			)
		);

		$this->authorizeUrl = 'https://graph.facebook.com/oauth/authorize?redirect_uri=http://' . env('SERVER_NAME') . '/services/oauth_callback/facebook/&client_id=' . $this->clientId . '&scope=publish_stream';

		$this->accessToken = 'https://graph.facebook.com/oauth/access_token';

		// key, secret
		parent::__construct($this->key, $this->secret);
	}

	// this takes a pointer of the access token, the consumer and the post.  it will then make the update using the info here
	public function post(&$accessToken, &$oauthConsumer, $post) {
		$oauthConsumer->post(
			$accessToken->key,
			$accessToken->secret,
			'http://api.twitter.com/1/statuses/update.json',
			array(
				'status' => $post
			)
		);
	}

}