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

	public function contacts($service = null) {
		App::import('Xml');
		$this->loadModel('Oauth');

		// Google contacts
		$this->OauthConsumer->begin($service);
		$accessToken = $this->Oauth->getAccessToken($service, $this->currentUser);

		// get out consumer class
		$consumer = $this->OauthConsumer->getConsumerClass();

		// send the request
		$contacts = $consumer->get($accessToken, $this->OauthConsumer);

		$this->set(compact('contacts'));
	}

	public function disconnect($consumer) {
		$this->loadModel('Oauth');

		$this->Oauth->disconnect($this->currentUser['User']['id'], $consumer);

		$this->redirect($this->referer());
	}

	public function index() {
		$this->loadModel('Oauth');

		// read all the oauth consumers and add them to the array of available connections
		$read = $write = array();
		// base dir for consumer files
		$consumerDir = ROOT . DS . APP_DIR . DS . 'controllers' . DS . 'components' . DS . 'oauth_consumers';
		// open the dir
		if ($handle = opendir($consumerDir)) {
			// import the abstract consumer, it's needed by the other consumer files
			App::Import('File', 'abstractConsumer', array('file' => $consumerDir . DS . 'abstract_consumer.php'));

			// loop over every file in the dir
			while (false !== ($file = readdir($handle))) {
				// make sure it's what we want
				if ($file != "." && $file != ".." && $file != 'abstract_consumer.php') {
					// grab the name of the service it's for
					$consumerName = explode('_consumer', $file);
					$consumerName = ucfirst($consumerName[0]);

					// generate the class name for the consumer
					$name = Inflector::classify(str_replace('.php' , '', $file));

					// import the consumer file
					App::Import('File', $name, array('file' => $consumerDir . DS . $file));
					// make it
					$consumer = new $name;

					// check if this consumer has expired, if it has it will be removed and have to be reconnected
					$this->Oauth->checkExpires($this->currentUser['User']['id'], $consumerName);

					// check if we're using post, if yes, we're writing
					if (method_exists($consumer, 'post')) {
						$write[] = array('Oauth' => array('name' => $consumerName, 'expires' => $consumer->expires));
					}
					// check if we're using get, if yes, we're reading
					if (method_exists($consumer, 'get')) {
						$read[] = array('Oauth' => array('name' => $consumerName, 'expires' => $consumer->expires));
					}
					unset($consumer);
				}
			}
			closedir($handle);
		}

		$connectedServices = array();
		foreach ($this->currentUser['Oauth'] as $service) {
			$connectedServices[] = $service['service'];
		}
		$this->set(compact('connectedServices', 'read', 'write'));
	}

}