<?php

namespace Famework\Exception;

class Famework_Exception extends \Exception {

    public function __construct($message, $code, $previous) {
        parent::__construct($message, $code, $previous);
    }

}
