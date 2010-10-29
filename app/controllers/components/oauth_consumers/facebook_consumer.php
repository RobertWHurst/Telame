<?php

class FacebookConsumer extends AbstractConsumer {
    public function __construct() {
    	// key, secret
        parent::__construct('key', 'secret');
    }
}
?>