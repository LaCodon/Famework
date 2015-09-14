<?php

namespace Famework\LaCodon\Mail;

use Famework\Exception\Famework_Exception_Invalid_Argument;

class Famework_Email {

    private $_email;

    public function __construct($email) {
        if (empty($email) || !is_string($email)) {
            throw new Famework_Exception_Invalid_Argument('The E-Mail has to be a valid one.');
        }

        if (strpos($email, '@') === FALSE) {
            throw new Exception_Invalid_Email();
        }

        if (preg_match("/[\r\n]/", $email)) {
            throw new Famework_Exception_Invalid_Argument('CRLF injection in E-Mail detected.');
        }

        $this->_email = $email;
    }

    public function getEmail() {
        return $this->_email;
    }

}
