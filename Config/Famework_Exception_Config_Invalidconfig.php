<?php

namespace Famework\Config;

use \Famework\Exception\Famework_Exception;

class Famework_Exception_Config_Invalidconfig extends Famework_Exception {

    public function __construct() {
        $message = '(!)<Config Error> The config file is invalid!';
        parent::__construct($message, 0, NULL);
    }

}
