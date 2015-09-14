<?php

namespace Famework\LaCodon\Mail;

use \Famework\Exception\Famework_Exception;

class Exception_Invalid_Email extends Famework_Exception {

    public function __construct() {
        parent::__construct('The E-Mail address is an invalid one.', 0, NULL);
    }

}
