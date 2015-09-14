<?php

namespace Famework\Exception;

class Famework_Exception_Invalid_Argument extends Famework_Exception {

    public function __construct($message) {
        parent::__construct($message, 0, NULL);
    }

}
