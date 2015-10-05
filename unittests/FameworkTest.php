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

    protected function setUp() {
        $this->_config = new Famework_Config('');
        $this->_routes = new Famework_Config('
[default]
famework_route = {root}/:controller/:action
famework_controller = :controller
famework_action = :action

');
        $this->_famework = new Famework($this->_config, $this->_routes);
    }

    /**
     * @dataProvider pathProvider
     */
    public function testHandle($path, $contExp, $actExp) {
        $this->_famework->handleRequest($path);
        $this->assertEquals($contExp, $this->_famework->getController());
        $this->assertEquals($actExp, $this->_famework->getAction());
    }
    
    public function pathProvider() {
        return array(
            array('/test/test', 'TestController', 'testAction'),
            array('/test/test/', 'TestController', 'testAction'),
            array('/test/test.do', 'TestController', 'testdoAction'),
            array('/test/test.do/', 'TestController', 'testdoAction'),
            array('/test/test/addfolder', 'TestController', NULL),
            array('/test/', NULL, NULL),
            array('/test', NULL, NULL)
        );
    }
    
    /**
     * Because of header:
     * @runInSeparateProcess
     */
    public function testLoad() {
         $this->_famework->loadController(TRUE); // no output
    }

}
