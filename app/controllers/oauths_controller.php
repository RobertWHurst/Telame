<?php
class OauthsController extends AppController {
	public $components = array('OauthConsumer');

	// this is where we connect to the oauth service
	public function oauth($service, $method = 'read') {
		// normalize service name
		$service = strtolower(ucfirst($service));
		// ensure security is the highest it can be without messing up
		Configure::write('Security.level', 'medium');

		// start our oauth consumer, passing the service name
		$this->OauthConsumer->begin($service);
		// get the info about our consumer
		$consumerClass = $this->OauthConsumer->getConsumerClass();

		// generate our request token using the info stored in the consumer class file
		$requestToken = $this->OauthConsumer->getRequestToken(
			$consumerClass->requestToken['url'],
			'http://' . env('SERVER_NAME') . '/services/oauth_callback/' . $service . '/' . $method,
			'POST',
			$consumerClass->requestToken['params']
		);

		// store the request token in the session vars
		$this->Session->write($service . '_request_token', serialize($requestToken));
		// send the user to the service page, so they can authorize us
		$this->redirect($consumerClass->authorizeUrl . $requestToken->key);
	}

	// after we've been authorized, we are sent back here
	public function oauth_callback($service, $method = 'read') {
		// normalize service name
		$service = strtolower(ucfirst($service));
		Configure::write('Security.level', 'medium');

		// start the consumer
		$this->OauthConsumer->begin($service);
		// get the info needed
		$consumerClass = $this->OauthConsumer->getConsumerClass();
		$expires = (isset($consumerClass->expires) ? $consumerClass->expires : null);

		// read our request token from the session
		$requestToken = unserialize($this->Session->read($service . '_request_token'));

		// get the access token from the service provider
		$accessToken = $this->OauthConsumer->getAccessToken($consumerClass->accessToken, $requestToken);

		// save the access token into the db for future use
		$this->Oauth->saveToDb($service, $this->currentUser['User']['id'], $accessToken, $method, $expires);
		// send em back to the services page
		$this->redirect(array('slug' => $this->currentUser['User']['slug'], 'controller' => 'services', 'action' => 'index'));
	}
}