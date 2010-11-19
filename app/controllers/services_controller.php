<?php
class ServicesController extends AppController {
	public $components = array('OauthConsumer');
	var $uses = array();

	public function beforeFilter(){
		parent::beforeFilter();

		$this->Security->blackHoleCallback = '_forceSSL';
		$this->Security->requireSecure('basic');
		if (!in_array($this->action, $this->Security->requireSecure) && env('HTTPS')) {
		 	$this->_unforceSSL();
		}
		$this->Auth->allow(array('confirm', 'signup'));

	}

	public function beforeRender() {
		parent::beforefilter();

		//set the layout
		$this->layout = 'tall_header_w_sidebar';
	}

	public function contacts() {
		App::import('Xml');
		$this->loadModel('Oauth');

		// Google contacts
		$this->OauthConsumer->begin('Google');
		$accessToken = $this->Oauth->getAccessToken('Google', $this->currentUser['User']['id']);

		$contacts = $this->OauthConsumer->get(
			$accessToken->key,
			$accessToken->secret,
			'https://www.google.com/m8/feeds/contacts/default/full?max-results=100'
		);
		$xml = new Xml($contacts);
		// This converts the Xml document object to a formatted array
		$contacts = Set::reverse($xml);
		// Or you can convert simply by calling toArray();
		$contacts = $xml->toArray();
		

		$this->OauthConsumer->begin('Yahoo');
		$accessToken = $this->Oauth->getAccessToken('Yahoo', $this->currentUser['User']['id']);

		$guid = $this->OauthConsumer->get(
			$accessToken->key,
			$accessToken->secret,
			'http://social.yahooapis.com/v1/me/guid'
		);

		$yahooContacts = $this->OauthConsumer->get(
			$accessToken->key,
			$accessToken->secret,
			'http://social.yahooapis.com/v1/user/' . $guid . '/contacts?count=max'
		);

pr($yahooContacts);
//http://social.yahooapis.com/v1/user//?format=json
//http://social.yahooapis.com/v1/me/guid


		$this->set(compact('contacts'));
	}

	public function index() {
		$this->loadModel('Oauth');
		$services = $this->Oauth->find('all', array('conditions' => array('user_id' => $this->currentUser['User']['id'])));

		$connectedServices = array();
		foreach ($services as $service) {
			$connectedServices[] = $service['Oauth']['service'];
		}
		$this->set(compact('connectedServices'));
	}

}