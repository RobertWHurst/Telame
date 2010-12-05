<?php
// ericfri
class TwitterConsumer extends AbstractConsumer {
	// These are sored in order used in the OAuth sequence
	public $requestToken = null;
	public $authorizeUrl = null;
	public $accessToken = null;
	public $expires = null;

	private $key = '';
	private $secret = '';

	public function __construct() {
		$this->requestToken = array(
			'url' => 'http://twitter.com/oauth/request_token',
			'params' => array()
		);

		$this->authorizeUrl = 'http://twitter.com/oauth/authorize?oauth_token=';

		$this->accessToken = 'http://twitter.com/oauth/access_token';

		// key, secret
		parent::__construct($this->key, $this->secret);
	}

	// this takes a pointer of the access token, the consumer and the post.  it will then make the update using the info here
	public function post(&$accessToken, &$oauthConsumer, $post) {
		App::import('Helper', 'Text');
		$this->Text = new TextHelper();
		$oauthConsumer->post(
			$accessToken->key,
			$accessToken->secret,
			'http://api.twitter.com/1/statuses/update.json',
			array(
				'status' => $this->Text->truncate($post, 140)
			)
		);
	}
}