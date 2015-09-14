<?php

namespace Famework\LaCodon\Param;

use Famework\Exception\Famework_Exception;

class Exception_Param extends Famework_Exception {

    public function __construct($message) {
        $message = '<Param Error> ' . $message;
        parent::__construct($message, 0, NULL);
    }

}
