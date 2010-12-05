<?php
// admin@telame.com
// *****Telame
class WindowsLiveConsumer extends AbstractConsumer {
	// These are sored in order used in the OAuth sequence
	public $requestToken = null;
	public $authorizeUrl = null;
	public $accessToken = null;
	public $expires = 30; // 30 minutes

	private $key = '';
	private $secret = '';

	public function __construct() {
		$this->requestToken = array(
			'url' => 'https://consent.live.com/Connect.aspx',
			'params' => array(
				'wrap_client_id' => $this->key,
				'wrap_callback' => 'http://' . env('SERVER_NAME') . '/services/oauth_callback/windows_live',
				'wrap_scope' => 'WL_Contacts.View',
			)
		);

		$this->authorizeUrl = 'https://consent.live.com/Connect.aspx?wrap_client_id=' . $this->key . '&wrap_callback=http://' . env('SERVER_NAME') . '/services/oauth_callback/windows_live&wrap_scope=WL_Contacts.View';

		$this->accessToken = '';

		// key, secret
		parent::__construct($this->key, $this->secret);
	}
}