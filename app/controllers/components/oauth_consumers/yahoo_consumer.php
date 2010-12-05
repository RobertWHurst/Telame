<?php
// telame@ymail.com
class YahooConsumer extends AbstractConsumer {
	// These are sored in order used in the OAuth sequence
	public $requestToken = null;
	public $authorizeUrl = null;
	public $accessToken = null;
	public $expires = 60;

	private $key = '';
	private $secret = '';

	public function __construct() {
		$this->requestToken = array(
			'url' => 'https://api.login.yahoo.com/oauth/v2/get_request_token',
			'params' => array()
		);

		$this->authorizeUrl = 'https://api.login.yahoo.com/oauth/v2/request_auth?oauth_token=';

		$this->accessToken = 'https://api.login.yahoo.com/oauth/v2/get_token';

		// key, secret
		parent::__construct($this->key, $this->secret);
	}

	// this takes a pointer of the access token, the consumer and retrieve the info we need
	public function get(&$accessToken, &$oauthConsumer) {
		// we need the GUID of the user for Yahoo
		$guid = $oauthConsumer->get(
			$accessToken->key,
			$accessToken->secret,
			'http://social.yahooapis.com/v1/me/guid'
		);
		// Yahoo returns XML, so break it apart and make it an array
		$xml = new Xml($guid);
		// Or you can convert simply by calling toArray();
		$guid = $xml->toArray();

		// get them contacts
		$contacts = $oauthConsumer->get(
			$accessToken->key,
			$accessToken->secret,
			'http://social.yahooapis.com/v1/user/' . $guid['Guid']['value']. '/contacts',
			array(
				'count' => 'max',
				'format' => 'xml',

			)
		);

		// return array
		$c = array();
		// counter
		$i = 0;
		// new xml object
		$xml = new Xml($contacts);
		$contacts = $xml->toArray();

		// let's break apart Yahoo's contact format and make it our own, extracting what we want
		foreach ($contacts['Contacts']['Contact'] as $contact) {
			foreach ($contact['Fields'] as $field) {
				if ($field['type'] == 'email') {
					$c[$i]['Contact']['email'][] = $field['value'];
				}
				if ($field['type'] == 'name' && isset($c[$i]['Contact']['email'])) {
					$firstName = (isset($field['Value']['givenName']) ? $field['Value']['givenName'] : '');
					$lastName = (isset($field['Value']['familyName']) ? $field['Value']['familyName'] : '');
					$c[$i]['Contact']['fullName'] = $firstName . ' ' . $lastName;
				}
			}
		$i++;
		}

		return $c;
	}
}