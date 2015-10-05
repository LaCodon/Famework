<?php

use Famework\Registry\Famework_Registry;
use Famework\Registry\Famework_Exception_Registry_Error;
use Famework\Db\Famework_Exception_Database_Fail;

class RegistryTest extends PHPUnit_Framework_TestCase {

    public function setUp() {
        Famework_Registry::set('foo', 'bar');
        Famework_Registry::setEnv('Test');
        // call errors once to autoload them, maybe PHPUnit bug
        (new Famework_Exception_Registry_Error(NULL, NULL));
        (new Famework_Exception_Database_Fail(NULL, NULL));
    }

    public function testGet() {
        $this->assertEquals('bar', Famework_Registry::get('foo'));
        $this->assertEquals(NULL, Famework_Registry::getView());
        $this->assertEquals('Test', Famework_Registry::getEnv());
    }

    /**
     * @expectedException Famework_Exception_Registry_Error
     */
    public function testGetEmpty() {
        Famework_Registry::get('empty', TRUE);
    }

    /**
     * @expectedException Famework_Exception_Database_Fail
     */
    public function testGetDb() {
        Famework_Registry::getDb();
    }

}
