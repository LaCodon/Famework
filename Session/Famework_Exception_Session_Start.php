<?php

namespace Famework\Session;

use \Famework\Exception\Famework_Exception;

class Famework_Exception_Session_Start extends Famework_Exception {

    public function __construct() {
        parent::__construct('<Session Error> The session couldn\'t get started. Please replace your Famework_Session usage.', 0, NULL);
    }

}
