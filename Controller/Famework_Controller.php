<?php

namespace Famework\Controller;

abstract class Famework_Controller {

    /**
     * @var Famework_View
     */
    protected $_view;

    public abstract function init();

    public function __construct($view) {
        $this->_view = $view;
    }

}
