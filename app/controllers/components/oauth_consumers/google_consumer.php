<?php
// http://google.com/a
// admin@telame.com
class GoogleConsumer extends AbstractConsumer {
	// These are sored in order used in the OAuth sequence
	public $requestToken = null;
	public $authorizeUrl = null;
	public $accessToken = null;
	public $expires = null;

	private $key = '';
	private $secret = '';

	public function __construct() {
		$scope = 'https://www.google.com/m8/feeds/';
		$this->requestToken = array(
			'url' => 'https://www.google.com/accounts/OAuthGetRequestToken',
			'params' => array('scope' => $scope)
		);

		$this->authorizeUrl = 'https://www.google.com/accounts/OAuthAuthorizeToken?oauth_token=';

		$this->accessToken = 'https://www.google.com/accounts/OAuthGetAccessToken';

		// key, secret
		parent::__construct($this->key, $this->secret);
	}

	// this takes a pointer of the access token, the consumer and retrieve the info we need
	public function get(&$accessToken, &$oauthConsumer) {
		$contacts = $oauthConsumer->get(
			$accessToken->key,
			$accessToken->secret,
			'https://www.google.com/m8/feeds/contacts/default/full',
			array(
				'max-results' => 1000,
			)
		);

		// our return array
		$c = array();
		// counter
		$i = 0;
		$xml = new Xml($contacts);
		// Or you can convert simply by calling toArray();
		$contacts = $xml->toArray();

		foreach($contacts['Feed']['Entry'] as $contact) {
			if (isset($contact['Email']['address'])) {
				$c[$i]['Contact']['email'][] = $contact['Email']['address'];

				// only do the name if we have an email
				$c[$i]['Contact']['fullName'] = (isset($contact['title']['value']) ? $contact['title']['value'] : '');
			}
			$i++;
		}

		return $c;
	}
}