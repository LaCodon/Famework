<?php

namespace Famework\Registry;

use \Famework\Exception\Famework_Exception;

class Famework_Exception_Registry_Error extends Famework_Exception {

    public function __construct($message, $previous = NULL) {
        $message = '(!)<Registry ERROR> ' . $message;
        parent::__construct($message, 0, $previous);
    }

}
