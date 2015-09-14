<?php

namespace Famework\View;

use \Famework\Exception\Famework_Exception;

class Famework_Exception_Missing_View extends Famework_Exception {

    public function __construct($action) {
        $message = '(!)<View Error> View "' . $action . '.phtml" not found!';
        parent::__construct($message, 0, NULL);
    }

}
