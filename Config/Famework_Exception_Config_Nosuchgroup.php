<?php

namespace Famework\Config;

use \Famework\Exception\Famework_Exception;

class Famework_Exception_Config_Nosuchgroup extends Famework_Exception {

    public function __construct($group) {
        $message = '(!)<Config Error> The group "' . $group . '" is not available in the config file.';
        parent::__construct($message, 0, NULL);
    }

}
