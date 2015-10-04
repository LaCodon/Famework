<?php

use Famework\Famework;
use Famework\Config\Famework_Config;

class FameworkTest extends PHPUnit_Framework_TestCase {

    /**
     * @var Famework_Config
     */
    private $_config;

    /**
     * @var Famework\Config 
     */
    private $_routes;

    /**
     * @var Famework
     */
    private $_famework;

    public function testConfig() {
        $this->_config = new Famework_Config('');
        $this->_routes = new Famework_Config('');
    }

    /**
     * @depends testConfig
     */
    public function testConstruct() {
        $this->_famework = new Famework($this->_config, $this->_routes);
    }

    /**
     * @depends testConstruct
     */
    public function testHandle() {
        $this->_famework->handleRequest();
        $this->_famework->loadController();
    }

}
