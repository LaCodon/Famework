<?php

namespace Famework\Db;

use \Famework\Exception\Famework_Exception;

class Famework_Exception_Database_Fail extends Famework_Exception {

    public function __construct($message, $previous = NULL) {
        $message = '(!)<Database Error> ' . $message;
        parent::__construct($message, 0, $previous);
    }

}
