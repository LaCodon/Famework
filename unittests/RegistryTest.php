<?php

use Famework\Registry\Famework_Registry;

class RegistryTest extends PHPUnit_Framework_TestCase {

    public function setUp() {
        Famework_Registry::set('foo', 'bar');
        Famework_Registry::setEnv('Test');
    }

    public function testGet() {
        $this->assertEquals('bar', Famework_Registry::get('foo'));
        $this->assertEquals(NULL, Famework_Registry::getView());
        $this->assertEquals('Test', Famework_Registry::getEnv());
    }

    /**
     * @expectedException Famework\Registry\Famework_Exception_Registry_Error
     */
    public function testGetEmpty() {
        Famework_Registry::get('empty', TRUE);
    }

    /**
     * @expectedException Famework\Db\Famework_Exception_Database_Fail
     */
    public function testGetDb() {
        Famework_Registry::getDb();
    }

}
