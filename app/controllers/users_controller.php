<?php
class UsersController extends AppController {

    var $name = 'Users';    
 
    function beforeFilter() {
        parent::beforeFilter();
        $this->Auth->loginRedirect = array('controller' => 'signup', 'action' => 'index');
    }

    function login() {
    }


    /** delegate /users/logout request to Auth->logout method */
    function logout() {
        $this->redirect($this->Auth->logout());
    }
}
?>